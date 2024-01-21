<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">3D Approval Page</span>
                </div>
                
                            
            </div>
        </div>
        <span class="text-red-500 text-[30px]">TO DO: PROCESS THE SUBMISSION ON BACKEND</span>
        <div  class="col-span-12 mt-8">
            <form @submit.prevent="submit" id="submitForm">
                <div class="flex flex-col">
                    <label for="title"  class="my-2">3D Name:</label>
                    <InputText id="title" v-model="form.title" class="w-full" required/>
                </div>

                <div class="my-4 ">
                    <label for="content"  >Description:</label>
                    <Textarea v-model="form.description" rows="5" cols="50" class="w-full " id="content" required />
                </div>
                
                <div class="mb-2">
                    <label for="imageAttachment" class="my-2" >Image:</label>
                    
                </div>

                <input type="file" accept="image/*" hidden id="imageAttachment" @input="Add3dImage"/>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <label for="imageAttachment" class="file-input-label bg-gray-300 px-4 py-2 rounded-md cursor-pointer w-[200px]">
                            Select image file... 
                        </label>
                        
                        <h1 class="ml-2">{{ threeDImageFileName }}</h1> 
                        <InputError :error="imageValidator" />
                    </div>
                    
                    <div v-if="imageUrl" class="mx-2 mt-2 p-1 bg-gray-200  inline-block relative  border border-gray-300  rounded-md " >
                        
                        <div v-if="imageUrl" class="flex justify-center items-center border border-gray-300 rounded-md p-2 shadow-md mt-2" >
                            <img :src="imageUrl" alt="Error" class="w-[50%] h-[50%] rounded-md relative"/>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="my-2">
                    <hr class="border border-1 border-gray-400">
                </div>
                <div class="w-full  my-1   py-2">
                    <h1 class="mb-4">3D File Link: </h1> 
                    <div class="w-full mb-4 ">
                        <span class="p-float-label">
                            <InputText v-model="form.threeDLink" id="3dLink"  class="w-full" />
                            <label for="3dLink">Enter file link</label>
                        </span>
                        <!-- <InputError :error="form.errors.title"/> -->
                    </div>
                    <div v-if="threeDValidator" class="mt-2">
                            <InputError :error="threeDValidator" />
                    </div>
                    <div v-if="isLoading" > 
                        <TestLinkLoadingSpinner :threeDLink="form.threeDLink"/>
                    </div>
                    
                </div>
                <div class="my-2">
                    <hr class="border border-1 border-gray-400">
                </div>
                <!-- submit button -->
                <div class="w-full mt-10">
                    <Button label="Submit" class="w-full " :disabled="form.processing" type="submit" />
                </div>
            </form>
        </div>
    </DashboardLayout>

</template>

<script setup>
import DashboardLayout from '../../../Layout/DashboardLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import InputError from '../../../../GlobalComponent/InputError.vue'
import {ref} from 'vue'
import TestLinkLoadingSpinner from '../../../../globalcomponent/testlinkloadingspinner.vue'
import axios from 'axios';


const user = usePage().props.user;

const form = useForm({
    title:null,
    description:null,
    image:null,
    threeDLink:null,
})

const imageUrl = ref(null);
const threeDImageFileName = ref(null);
const imageValidator = ref(null);
const imageIsValid = ref(false);
const Add3dImage = (event)=>{
    
    //console.log(event.target.files[0].size)
    if(event.target.files[0].type !== 'image/jpeg' || event.target.files[0].size > 3000000)
    {
        imageIsValid.value = false
        imageValidator.value = 'File should be of type "jpeg, jpg" with maximum size of 3mb';
        threeDImageFileName.value = null;
        imageUrl.value = null;
    }
    else
    {
        imageIsValid.value = true
        imageValidator.value = null;

        threeDImageFileName.value = event.target.files[0].name;

        form.image = event.target.files[0];
        
        imageUrl.value = URL.createObjectURL(event.target.files[0]);
    }

    
}

const threeDFilename = ref(null);
const threeDValidator = ref(null);
const threeDIsValid = ref(false);
const Add3dFile = (event) =>{

    if(!(
        (event.target.files[0].type === 'application/x-zip-compressed' ||
         event.target.files[0].type === 'application/x-compressed') &&
        event.target.files[0].size <= 50000000
    ))
    {
        threeDFilename.value = null
        threeDValidator.value = 'File should be of type ".zip, .rar" with maximum size of 50mb';
    }
    else
    {
        threeDIsValid.value = true;
        threeDValidator.value = ''
        threeDFilename.value = event.target.files[0].name;

        form.threeDFile = event.target.files[0];
    }
    
}

const googleDriveLinkStatus = ref(null);
const isLoading = ref(false);
const submit = ()=>{

    // Regular expression to match Google Drive download links
    const googleDriveRegex = /^https:\/\/drive\.google\.com\/.*$/;

    if(form.image === null)
    {
        imageValidator.value = 'Image is required.'
    }
    else if(form.threeDFile === null)
    {
        threeDValidator.value = '3d file is requred.'
    }
    else
    {
        
        if(imageIsValid.value === true)
        {
            
            async function validate(){
                    
                    if(googleDriveRegex.test(form.threeDLink))
                    {
                        isLoading.value = true;
                        await testGoogleDriveLink()

                        if(!googleDriveLinkStatus.value)
                        {
                            isLoading.value = false
                            threeDValidator.value = "The system cannot reach your Gdrive link, please ensure to provide a working link."
                            console.log(threeDValidator.value);
                        }
                        else
                        {
                            
                            threeDValidator.value = "";
                            isLoading.value = false;
                            form.post(route('3d.pending3dStore', {preserveScroll: true}))

                        }
                        
                    }
                    else
                    {
                        isLoading.value = false;
                        threeDValidator.value = 'Please provide a valid google drive link. ex: https://drive.google.com/your-download-link' 
                    }
                }
                
                validate();
        }
        
    }
    
}

async  function testGoogleDriveLink() 
{
    try {
        // Attempt to fetch the content of the Google Drive link
        const response = await axios.get(form.threeDLink, { maxRedirects: 0 });
        

        // Check if the response is a redirection (3xx) to the home page
        if (response.status >= 300 && response.status < 400 && response.headers.location === 'https://drive.google.com/') {
            googleDriveLinkStatus.value = false;
            console.log(googleDriveLinkStatus.value);
            
        } else {
            //Otherwise, consider it accessible
            googleDriveLinkStatus.value = response.status >= 200 && response.status < 300;
            
            //googleDriveLinkStatus.value = true;
            console.log(googleDriveLinkStatus.value);
        }
    } 
    catch (error) {
        // Handle errors, e.g., the link is not accessible
        console.error('Error testing Google Drive link: bakket');
        googleDriveLinkStatus.value = false;
        console.log(googleDriveLinkStatus.value);
    }
}
</script>