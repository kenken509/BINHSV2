<template>
    <DashboardLayout :user="user">
        <div class="flex justify-between items-center border-bot-only border-gray-600 shadow-md mb-4 py-4">
           <span class="text-[20px] font-bold text-gray-500">Rejected 3D Page </span>  

           <div class="">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="searchField" placeholder="search " id="searchFieldId" />
                </span>
            </div>
       </div>

       <div v-if="$page.props.flash.success" ><span class="p-3 text-gray-200">{{ successMessage($page.props.flash.success)  }}</span></div>
        <div v-if="$page.props.flash.error" class="flex items-center rounded-md bg-red-600 my-4 h-8 "><span class="p-3 text-gray-200">{{ $page.props.flash.error }}</span></div>
        <div v-if="filteredData" class="overflow-x-auto sm:-mx-6 lg:-mx-8 mt-4 overflow-x">
            <div class="inline-block min-w-full py-2 sm:px-6  lg:px-8">
                <div class="overflow-x-scroll rounded-lg">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="border-b font-medium text-gray-200  bg-green-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center">ID #</th>
                                <th scope="col" class="px-6 py-4 text-center">Title</th>
                                <th scope="col" class="px-6 py-4 text-center">Image</th>
                                <th scope="col" class="px-6 py-4 text-center">Description</th>
                                <th scope="col" class="px-6 py-4 text-center">Link</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="rejected in filteredData"
                            :key="rejected.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ rejected.id}}</td> 
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ rejected.title}}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="rejected.image ? appUrl+rejected.image: appUrl+'Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class=" px-6 py-4 text-center">
                                    <div class="w-[300px] text-justify whitespace-pre-line ">
                                        <span v-if="rejected.showFullDescription">
                                            {{ rejected.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(rejected)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(rejected.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(rejected)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="rejected.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-red-500 text-center">{{ rejected.status}}</td>
                            
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class=" space-x-6" >
                                        <span class="pi pi-trash text-red-700 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Delete File'" @click="deleteConfirmation(rejected.id)"></span>
                                                                                
                                        <!-- <span class="pi pi-pencil text-green-600 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Edit'" @click="handleEditFile(rejected.id)" ></span> -->
                                        <!-- <Link :href="route('3d.pending3dEdit', {id:rejected.id} )" class="cursor-pointer hover:dark:scale-125" v-tooltip.left="'Edit'" ><span class="pi pi-pencil text-green-600 scale-110 hover:dark:scale-150"></span></Link> -->
                                    </div>
                                    
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--ALL PENDING rejectedS-->
        <div v-else  class="overflow-x-auto sm:-mx-6 lg:-mx-8 mt-4 overflow-x">
            <div class="inline-block min-w-full py-2 sm:px-6  lg:px-8"> 
                <div class="overflow-x-scroll rounded-lg">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="border-b font-medium text-gray-200  bg-green-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center">ID #</th>
                                <th scope="col" class="px-6 py-4 text-center">Title</th>
                                <th scope="col" class="px-6 py-4 text-center">Image</th>
                                <th scope="col" class="px-6 py-4 text-center">Description</th>
                                <th scope="col" class="px-6 py-4 text-center">Link</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="rejected in data.rejected3d.data"
                            :key="rejected.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ rejected.id}}</td> 
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ rejected.title}}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="rejected.image ? appUrl+rejected.image: appUrl+'Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class=" px-6 py-4 text-center">
                                    <div class="w-[300px] text-justify whitespace-pre-line ">
                                        <span v-if="rejected.showFullDescription">
                                            {{ rejected.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(rejected)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(rejected.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(rejected)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="rejected.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-red-500 text-center">{{ rejected.status}}</td>
                            
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class=" space-x-6" >
                                        <span class="pi pi-trash text-red-700 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Delete File'" @click="deleteConfirmation(rejected.id)"></span>
                                                                                
                                        <!-- <span class="pi pi-pencil text-green-600 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Edit'" @click="handleEditFile(rejected.id)" ></span> -->
                                        <!-- <Link :href="route('3d.pending3dEdit', {id:rejected.id} )" class="cursor-pointer hover:dark:scale-125" v-tooltip.left="'Edit'" ><span class="pi pi-pencil text-green-600 scale-110 hover:dark:scale-150"></span></Link> -->
                                    </div>
                                    
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="data.rejected3d.data.length > 1"  class="w-full flex justify-center mt-8 mb-8">
                <Pagination :links="data.rejected3d.links"/>    
            </div>
        </div>
        
               <!--ALL PENDING rejectedS  v-if="users.data.length && allUsersVisible"-->
    </DashboardLayout>
    
</template>

<script setup>
import DashboardLayout from '../../../Layout/DashboardLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue'
import Pagination from '../../../AdminComponents/Pagination.vue';
import { toUpperFirst } from '../../../../Functions/Methods.vue';
import Swal from 'sweetalert2';


const user = usePage().props.user

const appUrl = ref('/storage/')
const data = defineProps({
    rejected3d:Object,
    searchRejected3d:Array,
})


const toggleDescription = (rejected) => {
    rejected.showFullDescription = !rejected.showFullDescription;
};

const truncateDescription = (description) => {
    // Split the description into words
    const words = description.split(' ');

    // Take the first five words and join them back into a string
    return words.slice(0, 3).join(' ');
};

// Iterate through the data and add a property to track whether the full description is shown
data.rejected3d.data.forEach(rejected => {
    rejected.showFullDescription = false;
});


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

function deleteConfirmation(fileId)
{
    Swal.fire({
        title:'Delete confirmation',
        text:"You won't be able to revert this!",
        icon:'warning',
        confirmButtonText:'Yes, delete it!',
        cancelButtonText:'Cancel',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        allowOutsideClick:false,
        allowEscapeKey:false,
    }).then((result)=>{
        if(result.isConfirmed)
        {
            const deleteUrl = route('3d.rejected3dDelete', {id: fileId});
            
            router.delete(deleteUrl);
        }

        if(result.isDismissed)
        {
            Swal.fire({
                title:'Canceled',
                text:'Your action was canceled!',
                icon:'error',
            })
        }
    })
}

//search logic
const searchField = ref(null)
const filteredData = ref(null)

watch(searchField,(val)=>{
    console.log(val)

    updateFilteredUsers();
})

function updateFilteredUsers() {
    if (!searchField.value || searchField.value.trim() === '') {
        filteredData.value = '';
        return;
    }

    const searchTerm = searchField.value.toLowerCase();

    filteredData.value = data.searchRejected3d.filter(searchRejected3d => {
        const isInstructorMatch = (
            searchRejected3d.instructor.fName.toLowerCase().includes(searchTerm) ||
            searchRejected3d.instructor.lName.toLowerCase().includes(searchTerm)
        );

        

        const isStatusMatch = searchRejected3d.status.toLowerCase().includes(searchTerm);

        const isDescriptionMatch = searchRejected3d.description.toLowerCase().includes(searchTerm);

        

        return (
            searchRejected3d.title.toLowerCase().includes(searchTerm) || isDescriptionMatch || isStatusMatch
        );
    });
}
</script>