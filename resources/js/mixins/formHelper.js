export default {
    methods: {
        getValidationListFromResponse: function (APIerrorsObject) {
            let validationErrorsOutput = [];
            for (var errorsPerField in APIerrorsObject) {
                APIerrorsObject[errorsPerField].forEach(errorMessage => {
                    validationErrorsOutput.push(errorMessage);
                });
            }

            return validationErrorsOutput;
        },
        getPayloadFromFormElements: function (formElements) {
            const payload = {};
            for (const elm of formElements) {
                if(elm instanceof HTMLTextAreaElement || elm instanceof HTMLInputElement) {
                    payload[elm.name] = elm.value;
                }
            }

            return payload;
        }
    }
};