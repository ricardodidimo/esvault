export default {
    data() {
        return {
            httpMethods: {
                get: 'get',
                post: 'post',
                put: 'put',
                delete: 'delete'
            }
        }
    },
    methods: {
        defaultHandler: function () {
            console.log('unexpected error');
        },
        makeRequest: async function (method, action, options = {}) {
            try {
                await axios.get("/sanctum/csrf-cookie");
                const apiResponse = method === 'get' || method === 'delete' ? 
                    await axios[method](action) :
                    await axios[method](action, options.payload ?? []);
                switch (apiResponse.status) {
                    case 200:
                        if (options.handle200 === undefined) {
                            this.defaultHandler();
                            break;
                        }
                        options.handle200.handlerFunc(apiResponse, ...options.handle200.args ?? []);
                        break;
                    case 201:
                        if (options.handle201 === undefined) {
                            this.defaultHandler();
                            break;
                        } 
                        options.handle201.handlerFunc(apiResponse, ...options.handle201.args ?? []);
                        break;
                    case 204:
                        if (options.handle204 === undefined) {
                            this.defaultHandler();
                            break;
                        } 
                        options.handle204.handlerFunc(apiResponse, ...options.handle204.args ?? []);
                        break;
                    default:
                        this.defaultHandler();
                        break;
                }
            }catch (error) {
                const errorResponse = error.response;
                console.log(error);
                switch (errorResponse.status) {
                    case 400:
                        if (options.handle400 === undefined) {
                            this.defaultHandler();
                            break;
                        } 
                        options.handle400.handlerFunc(errorResponse, ...options.handle400.args ?? []);
                        break;
                    case 401:
                        if (options.handle401 === undefined) {
                            this.defaultHandler();
                            break;
                        } 
                        options.handle401.handlerFunc(errorResponse, ...options.handle401.args ?? []);
                        break;
                    case 403:
                        if (options.handle404 === undefined) {
                            this.defaultHandler();
                            break;
                        } 
                        options.handle403.handlerFunc(errorResponse, ...options.handle403.args ?? []);
                        break;
                    default:
                        this.defaultHandler();
                        break;
                }
            }

        }
    }
};