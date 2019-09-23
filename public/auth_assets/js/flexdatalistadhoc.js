let flexArray = [ {
    name: 'flex0',
    hiddenName: 'hiddenJobTitleFiltered',
    openName:'jobTitles'
},
{
    name: 'flex1',
    hiddenName: 'hiddenCompaniesFiltered',
    openName:'companies'
},
{
    name: 'flex2',
    hiddenName: 'hiddenSkillsFiltered',
    openName:'skills'
},
{
    name: 'flex3',
    hiddenName: 'hiddenIndustryFiltered',
    openName:'industry'
},
{
    name: 'flex4',
    hiddenName: 'hiddenUniversityFiltered',
    openName:'university'
},
{
    name: 'flex5',
    hiddenName: 'hiddenCandidateCourseFiltered',
    openName: 'candidateCourse'
},
{
    name: 'flex6',
    hiddenName: 'hiddenCandidateDegreesFiltered',
    openName:'candidateDegrees'
},
{
    name: 'flex7',
    hiddenName: 'hiddenCandidateQualificationsFiltered',
    openName:'candidateQualifications'
},
{
    name: 'flex8',
    hiddenName: 'hiddenCandidateResidentialStateFiltered',
    openName:'candidateResidentialState',
},
{
    name: 'flex9',
    hiddenName: 'hiddenStateOfOriginFiltered',
    openName:'candidate_state_of_origin'
    },
    {
        name: 'flex10',
        hiddenName: 'hiddenCandidateCertificationsFiltered',
        openName:'candidate_certifications'
    },
];

function runFlex0() {
    flexArray.forEach((flexitem)=>{
        let filteredString = '';
        let filteredArray = [];
        $('.' + flexitem.name + ' > li').find("span").each(function() {
            let item = $(this).text();
            if(item!='×') {
                filteredArray.push(item.trim());
            }
        });
        filteredString = filteredArray.join(",")
        document.getElementById(flexitem.hiddenName).innerHTML =  `<input type="hidden" name=${flexitem.openName} value="${filteredString}">`;
        filteredString = '' ?  document.getElementById(flexitem.hiddenName).innerHTML = null : 0;
    });
}

function clearFilterModal() {
   rangeInputs = document.getElementsByClassName('r-input');
    Array.from(rangeInputs).forEach((input) => {
        input.value = '';
    });
}

$("#nysc_status").on("click", function(e) {
    if ($(this).is( ":checked" )) {
        document.getElementById("nysc_status").setAttribute('value','Completed');
    } else {
        document.getElementById("nysc_status").removeAttribute('value');
    }
});

$( document ).ready(function() {
    document.body.addEventListener('click', function (evt) {
        if (evt.target.classList.contains('shortlistInput') ||  evt.target.classList.contains('flexdatalist-multiple')) {
                $("#saveShortListingFilterId").attr('data-active',true);
                $("#saveShortListingFilterId").css({'opacity':'1','cursor':'pointer'});
                document.getElementById(evt.target.id) ?  document.getElementById(evt.target.id).removeAttribute('placeholder') : null;
        }
    }, false);
    aliasInputs = document.getElementsByClassName('flexdatalist-alias');
    Array.from(aliasInputs).forEach((input) => {
        input.setAttribute('autocomplete','nope');
    });
});
