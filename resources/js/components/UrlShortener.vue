<template>
    <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row shortner">
                       <div class="section-heading text-center">
                                <h1>URL Shortener</h1>
                                <br>
                                <div class="py-2" align="center">
                                    <div class="pageIntroDescription border border-info p-3 mb-0">
                                        Build and protect your brand using powerful, recognizable short links. A free and
                                        advanced URL Shortener, Use it freely and share with others.
                                    </div>
                                </div>
                                <br><br>
                           
                                <div v-if="AuthorizedUser">
                                        <form class="form">
                                            <div class="input-group mb-3">
                                                <input type="text" id="p1" v-model="url" class="form-control addUrlInput"
                                                    placeholder="Add Url Here" aria-label="Add Url Here"
                                                    aria-describedby="button-addon2">
                                            </div>
                                            <div>
                                                <button v-on:click.prevent="shortenUrl" class="btn btn-dark" id="button-addon2">Add
                                                    Url, And Click here
                                                </button>
                                                <button v-on:click.prevent="lookup" class="btn btn-gray" id="button-lookup">
                                                    Look up
                                                </button>
                                            </div>
                                        </form>
                                        <br>
                                        <p v-if="urlNotFound==false" class="alert alert-danger">Url Is Not Valid</p>

                                        <div class="copyLink mb-5">
                                            <span id="output_url"></span>
                                            <span id="clipBoard" v-on:click.prevent="copyContent">
                                                <i class="far fa-copy"></i> 
                                                {{copyTextString}} 
                                            </span>
                                        </div>
                                        <div>
                                            <span id="check_url"></span>
                                        </div>
                                </div>
                                <div class="alert-info p-3" v-else>
                                    <h5>
                                        You are required to register with us,
                                        <hr>
                                        To shortn your URls
                                        <hr>
                                        <a href="/register">
                                            <small>
                                                Register Here
                                            </small>
                                        </a>
                                        or
                                        <a href="/login">
                                            <small>
                                                Log In Here
                                            </small>
                                        </a>
                                    </h5>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log(this.AuthorizedUser+'Component mounted.');
        },
        props : ["AuthorizedUser"],
        data(){
            return {
                url: '',
                urlNotFound: true,
                copyTextString: 'Copy Text To Clipboard',
                response: null,
            }
        },
        methods: {
            shortenUrl() {
                    let self = this;
                    let newUrl = self.url;
                    let newArray = newUrl.split('/');
                    console.log('array count',newArray.length);
                    let counter = 0;
                    let resultNewUrl = Math.round((Math.pow(36, 7) - Math.random() * Math.pow(36, 7))).toString(36).slice(1);


                    for (let i = 0; i < newArray.length; i++) {
                        if (newArray[i] == 'http:' || newArray[i] == 'https:') {
                            counter += 1;
                        }
                        if (counter == 0) {
                            let newArrayOne = newUrl.split('.');
                            if (newArrayOne[i] == 'www') {
                                counter += 1;
                            }
                        }
                        if (counter == 0) {
                            let newArrayTwo = newUrl.indexOf('.com');
                            if (newArrayTwo >= 0) {
                                counter += 1;
                            }
                        }
                    }
                    if (counter == 0) {
                        self.urlNotFound = false;
                    } else if (counter > 0) {
                        let newshortlink = '';
                        if(newArray[0] == 'http:' || newArray[0] == 'https:'){
                            if(newArray.length > 3){
                                newshortlink = newArray[0] + '//' + newArray[2] + '/' + resultNewUrl;
                            }else if(newArray.length == 3){
                                newshortlink = newUrl;
                            }
                        }else{
                            if(newArray.length > 1){
                                newshortlink = newArray[0] + '/' + resultNewUrl;
                            }else{
                                newshortlink = newUrl;
                            }
                        }
                        axios.post('/url/shorten', {
                            url: newUrl,
                            shortlink: newshortlink
                        })
                            .then(function (response) {
                                if(response.data === 3){
                                    toastr.error('<h5 style="color:#fff; font-weight:700;">You Already Have this Url Shortened</h5>');
                                    return false;
                                };

                                self.response  = response.data;
                                $("#output_url").html(self.response);
                                $(".copyLink").fadeIn(500);
                                $("#output_url").parent().siblings(".form").find("#p1").val(self.response);

                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                    //console.log(newArray);
                },
                copyContent() {
                    let id = $("#p1").select();
                    this.copyTextString = 'URL Coppied Successfully';
                    document.execCommand("copy");
                    this.url = this.response;
                },
                async lookup() {
                    let self = this;
                    let newUrl = self.url;
                    console.log(newUrl);
                    try {
                        const apiKey = 'AIzaSyD3uFWKmWWDoGavt2BIhEOrVU0PG4GoPfs';
                        const apiUrl = `https://safebrowsing.googleapis.com/v4/threatMatches:find?key=${apiKey}`;

                        const requestData = {
                        client: {
                            clientId: '691935475394-e8f4gka99b9cnitse8h0eqs8kkhbgcn5.apps.googleusercontent.com',
                            clientVersion: '1.0.0',
                        },
                        threatInfo: {
                            threatTypes: ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                            platformTypes: ['ANY_PLATFORM'],
                            threatEntryTypes: ['URL'],
                            threatEntries: [{ url: newUrl }],
                        },
                        };
                        console.log(requestData);

                        const response = await axios.post(apiUrl, requestData);
                        if (response.data.matches && response.data.matches.length > 0) {
                            this.result = 'This URL is unsafe!';
                            $("#check_url").html(this.result);
                        } else {
                            this.result = 'This URL is safe.';
                            $("#check_url").html(this.result);
                        }
                    } catch (error) {
                        console.error('Error during Safe Browsing lookup:', error);
                    }
                    console.log(this.result);
                },
        }
    }
</script>
<style scoped>
    .copyLink{
        display:none;
    }

    #clipBoard{
        display: block;
        margin-top: 28px;
        background-color: #03cbf8;
        color: #fff;
        font-weight: 900;
        font-size: 17px;
    }

    #clipBoard:hover{
        background-color: #333;
    }

    #clipBoard:visited, #clipBoard:active, #clipBoard:focus{
        background-color: rgb(7, 190, 1);
        color:#333;
    }

</style>