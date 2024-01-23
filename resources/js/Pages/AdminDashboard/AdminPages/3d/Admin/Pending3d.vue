<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">3D Approval Page</span>
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
                                <th scope="col" class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="pending3d in filteredData"
                            :key="pending3d.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ pending3d.id}}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ pending3d.title}}</td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="pending3d.image ? appUrl+pending3d.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <div class="w-[200px] text-justify whitespace-pre-line ">
                                        <span v-if="pending3d.showFullDescription">
                                            {{ pending3d.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(pending3d)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(pending3d.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(pending3d)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="pending3d.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ pending3d.instructor.subject.name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">{{ toUpperFirst(pending3d.instructor.lName) }} {{ toUpperFirst(pending3d.instructor.fName) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center text-red-500">{{ pending3d.status }}</td>
                            

                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class="flex space-x-10" >
                                        <div class="cursor-pointer hover:scale-125">
                                            <span class="pi pi-check-circle text-green-600 scale-150 hover:scale-150" v-tooltip.left="'approve'" @click="approvalConfirmation(pending3d.id)"></span>
                                        </div>
                                        
                                        <div class="cursor-pointer hover:scale-125" v-tooltip.left="'reject'">
                                            
                                            <span class="pi pi-times-circle text-red-700 scale-150 hover:scale-150" @click="rejectConfirmation(pending3d.id)"></span>
                                        </div>
                                    </div>                                      
                                </td>
                            </tr>                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else class="overflow-x-auto sm:-mx-6 lg:-mx-8  overflow-x">
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
                                    <th scope="col" class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                v-for="pending3d in data.pending3d.data"
                                :key="pending3d.id"
                                
                                class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                    
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ pending3d.id}}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ pending3d.title}}</td>
                                    
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        
                                        <Image :src="pending3d.image ? appUrl+pending3d.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                            <template #indicator>
                                                <i class="pi pi-eye"></i>
                                            </template>
                                        </Image>     
                                    </td>  
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                        <div class="w-[200px] text-justify whitespace-pre-line ">
                                            <span v-if="pending3d.showFullDescription">
                                                {{ pending3d.description }}
                                                <button class="text-blue-700 underline ml-2" @click="toggleDescription(pending3d)">
                                                    Read Less
                                                </button>
                                            </span>
                                            <span v-else>
                                                {{ truncateDescription(pending3d.description) }}
                                                <button class="text-blue-700 underline ml-2" @click="toggleDescription(pending3d)">
                                                    Read More
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                        <a :href="pending3d.threeDLink" target="_blank" class="  ">
                                            <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                            <span class="underline ml-2">Gdrive Link</span>
                                        </a>
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-6 py-4 text-center">{{ availableSubjects[pending3d.instructor.subject_id-1]  }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">{{ toUpperFirst(pending3d.instructor.lName) }} {{ toUpperFirst(pending3d.instructor.fName) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-red-500">{{ pending3d.status }}</td>
                                

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex space-x-10" >
                                            <div class="cursor-pointer hover:scale-125">
                                                <span class="pi pi-check-circle text-green-600 scale-150 hover:scale-150" v-tooltip.left="'approve'" @click="approvalConfirmation(pending3d.id)"></span>
                                            </div>
                                            
                                            <div class="cursor-pointer hover:scale-125" v-tooltip.left="'reject'">
                                                
                                                <span class="pi pi-times-circle text-red-700 scale-150 hover:scale-150" @click="rejectConfirmation(pending3d.id)"></span>
                                            </div>
                                        </div>                                      
                                    </td>
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
    pending3d:Object,
    searchPending3d:Array,
})

const toggleDescription = (pending3d) => {
    pending3d.showFullDescription = !pending3d.showFullDescription;
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
    })
}

//search logic
const searchField = ref(null);
const filteredData = ref(null);

watch(searchField,(val)=>{
   

    updateFilteredUsers();
})

function updateFilteredUsers() {
    console.log(searchField.value);
    if (!searchField.value || searchField.value.trim() === '') {
        filteredData.value = '';
        return;
    }

    const searchTerm = searchField.value.toLowerCase();

    filteredData.value = data.searchPending3d.filter(searchPending3d => {
        const isInstructorMatch = (
            searchPending3d.instructor.fName.toLowerCase().includes(searchTerm) ||
            searchPending3d.instructor.lName.toLowerCase().includes(searchTerm)
        );

        const isSubjectMatch = searchPending3d.instructor.subject.name.toLowerCase().includes(searchTerm);

        return (
            searchPending3d.title.toLowerCase().includes(searchTerm) ||
            searchPending3d.description.toLowerCase().includes(searchTerm) ||
            isInstructorMatch ||
            isSubjectMatch
        );
    });
}
</script>