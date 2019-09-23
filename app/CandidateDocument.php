<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class CandidateDocument extends Model
{
    protected $fillable = [
        'uuid','candidate_id', 'document_id', 'doc_name','doc_url'
    ];
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_id', 'uuid');
    }
    public function checkDocID($value)
    {
        $doc = Self::where('uuid', $value)->where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->first();
        if (!$doc)
            abort(404);
        return $doc;
    }

    public function checkAndDeleteIfDocExist($doc_type){
        $doc = self::where('document_id',$doc_type)->where('candidate_id',Auth::guard('candidate')->user()->candidate_id)->first();
        if($doc){
            $prv_url = str_replace("https://" . config('filesystems.disks.s3.bucket') . ".s3." . config('filesystems.disks.s3.region') . ".amazonaws.com", "", $doc->doc_url);
            Storage::disk('s3')->delete($prv_url);
            $doc->delete();
        }
    }

    public function createDoc($request)
    {
        $this->checkAndDeleteIfDocExist($request->input('doc_type'));
        list($doc_url, $doc_name) = $this->uploadDocs($request);
        $data['document_id'] = $request->input('doc_type');
        $data['doc_url'] = $doc_url;
        $data['doc_name'] = $doc_name;
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['candidate_id'] = Auth::guard('candidate')->user()->candidate_id;
        return self::create($data);
    }

    /**
     * @param $data
     * @return array
     */
    public static function uploadDocs($data)
    {
        if ($data->hasFile('doc_file')) {

            $filenamewithextension = $data->file('doc_file')->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $data->file('doc_file')->getClientOriginalExtension();
            $filenametostore = 'candidate/document/' . trim($filename) . '_' . time() . '.' . $extension;
            $result = Storage::disk('s3')->put($filenametostore, fopen($data->file('doc_file'), 'r+'), 'public');

            if ($result) {
                $doc_url = Storage::disk('s3')->url($filenametostore);
                $doc_name = $filenamewithextension;
            }
            return [$doc_url, $doc_name];
        }
        return [null, null];
    }

}
