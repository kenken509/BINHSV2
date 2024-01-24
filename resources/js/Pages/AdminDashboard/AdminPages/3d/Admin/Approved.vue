<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">3D Approved Page</span>
                </div>
                
                <div class="">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="searchField" placeholder="search " id="searchId" />
                    </span>
                </div>                
            </div>
        </div>
        
        
        <div v-if="$page.props.flash.success" ><span class="p-3 text-gray-200">{{ successMessage($page.props.flash.success)  }}</span></div>
        <div v-if="$page.props.flash.error" class="flex items-center rounded-md bg-red-600 my-4 h-8 "><span class="p-3 text-gray-200">{{ $page.props.flash.error }}</span></div>
        <div v-if="filteredData" class="overflow-x-auto sm:-mx-6 lg:-mx-8  overflow-x">
           
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="text-xs text-gray-200 uppercase bg-green-700  ">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center">ID #</th>
                                <th scope="col" class="px-6 py-4 text-center ">Title</th>
                                <th scope="col" class="px-6 py-4 text-center">Image</th> 
                                <th scope="col" class="px-6 py-4 text-center ">Description</th>
                                <th scope="col" class="px-6 py-4 text-center">Link</th>
                                <th scope="col" class="px-6 py-4 text-center">Subject</th>
                                <th scope="col" class="px-6 py-4 text-center">Uploaded By</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="approved3D in filteredData"
                            :key="approved3D.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ approved3D.id}}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ approved3D.title}}</td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="approved3D.image ? appUrl+approved3D.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <div class="w-[200px] text-justify whitespace-pre-line ">
                                        <span v-if="approved3D.showFullDescription">
                                            {{ approved3D.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(approved3D)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(approved3D.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(approved3D)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="approved3D.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ approved3D.instructor.subject.name  }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ toUpperFirst(approved3D.instructor.lName) }} {{ toUpperFirst(approved3D.instructor.fName) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center text-red-500">{{ approved3D.status }}</td>
                            </tr>             
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
        <div v-else  class="overflow-x-auto sm:-mx-6 lg:-mx-8  overflow-x">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="text-xs text-gray-200 uppercase bg-green-700  ">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center">ID #</th>
                                <th scope="col" class="px-6 py-4 text-center ">Title</th>
                                <th scope="col" class="px-6 py-4 text-center">Image</th> 
                                <th scope="col" class="px-6 py-4 text-center ">Description</th>
                                <th scope="col" class="px-6 py-4 text-center">Link</th>
                                <th scope="col" class="px-6 py-4 text-center">Subject</th>
                                <th scope="col" class="px-6 py-4 text-center">Uploaded By</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="approved3D in data.approved3D.data"
                            :key="approved3D.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ approved3D.id}}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ approved3D.title}}</td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="approved3D.image ? appUrl+approved3D.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <div class="w-[200px] text-justify whitespace-pre-line ">
                                        <span v-if="approved3D.showFullDescription">
                                            {{ approved3D.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(approved3D)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(approved3D.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(approved3D)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="approved3D.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ availableSubjects[approved3D.instructor.subject_id-1]  }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ toUpperFirst(approved3D.instructor.lName) }} {{ toUpperFirst(approved3D.instructor.fName) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center text-red-500">{{ approved3D.status }}</td>
                            </tr>                                   
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </DashboardLayout>
   
</template>

<script setup>
import DashboardLayout from '../../../Layout/DashboardLayout.vue';
import { usePage,router } from '@inertiajs/vue3';
import { ref, watch } from 'vue'
import { toUpperFirst } from '../../../../Functions/Methods.vue';
import Swal from 'sweetalert2';


const appUrl = ref('/storage/')
const user = usePage().props.user;

const data = defineProps({
    approved3D:Object,
    searchApproved3D:Array,
})

const toggleDescription = (approved3D) => {
    approved3D.showFullDescription = !approved3D.showFullDescription;
};

const truncateDescription = (description) => {
    // Split the description into words
    const words = description.split(' ');

    // Take the first five words and join them back into a string
    return words.slice(0, 2).join(' ');
};

const availableSubjects = ref([
    'Agri-Fishery Arts',
    'HOME ECONOMICS',
    'ICT',
    'INDUSTRIAL ARTS',
])



function approvalConfirmation(fileId)
{
    
    Swal.fire({
        title:'Approval Confirmation',
        text:'Are you sure you?',
        confirmButtonText:'Yes, approve it!',
        cancelButtonText:'Cancel',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        icon:'question',
    }).then((result)=>{
        if(result.isConfirmed)
        {
            const approveUrl = route('3d.approve3d',{id:fileId})
            
            router.get(approveUrl);
        }

        if(result.isDismissed)
        {
            Swal.fire({
                title:'Canceled',
                text:'Your action was canceled!',
                icon:'error',
                confirmButtonColor: '#3085d6',
            })
        }
    })
}

function rejectConfirmation(fileId)
{
    
    Swal.fire({
        title:'Rejection Confirmation',
        text:'Are you sure you?',
        confirmButtonText:'Yes, reject it!',
        cancelButtonText:'Cancel',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        icon:'question',
    }).then((result)=>{
        if(result.isConfirmed)
        {
            const rejectUrl = route('3d.reject3d',{id:fileId})
            
            router.get(rejectUrl);
        }

        if(result.isDismissed)
        {
            Swal.fire({
                title:'Canceled',
                text:'Your action was canceled!',
                icon:'error',
                confirmButtonColor: '#3085d6',
            })
        }
    })
}
function successMessage(message)
{
    Swal.fire({
        title:'Success',
        text:message+'!',
        icon:'success',
        allowOutsideClick:false,
        allowEscapeKey:false,
    }).then((result)=>{
        if(result.isConfirmed)
        {
            location.reload();
        }
    })
}

// search logic
const searchField = ref(null);

watch(searchField,(val)=>{
    console.log(val)

    updateFilteredUsers();
})
const filteredData = ref(null);
function updateFilteredUsers() {
    if (!searchField.value || searchField.value.trim() === '') {
        filteredData.value = '';
        return;
    }

    const searchTerm = searchField.value.toLowerCase();

    filteredData.value = data.searchApproved3D.filter(approved3D => {
        const isInstructorMatch = (
            approved3D.instructor.fName.toLowerCase().includes(searchTerm) ||
            approved3D.instructor.lName.toLowerCase().includes(searchTerm)
        );

        const isSubjectMatch = approved3D.instructor.subject.name.toLowerCase().includes(searchTerm);

        return (
            approved3D.title.toLowerCase().includes(searchTerm) ||
            approved3D.description.toLowerCase().includes(searchTerm) ||
            isInstructorMatch ||
            isSubjectMatch
        );
    });
}

</script>