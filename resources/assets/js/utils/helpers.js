export const formatDocTypes = (docTypes) => {
    if(Array.isArray(docTypes)){
        return docTypes.map((type) => {
            return {label: type.name, value: type.uuid}
        });
    }
    return [];
};
