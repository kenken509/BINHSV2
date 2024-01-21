<template>
    <DashboardLayout :user="user">
        <div class="flex justify-between items-center border-bot-only border-gray-600 shadow-md mb-4 py-4">
           <span class="text-[20px] font-bold text-gray-500">Pending Page </span>  

           <div class="">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <!-- <InputText v-model="searchField" placeholder="search " @input="handleSearchFieldInput" /> -->
                </span>
            </div>
       </div>
       {{  }}
        <!--ALL PENDING UPLOADS-->
        <div  class="overflow-x-auto sm:-mx-6 lg:-mx-8 mt-4 overflow-x">
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
                                <th scope="col" class="px-6 py-4 text-center">Uploader</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="upload in data.pending3d.data"
                            :key="upload.id"
                            
                            class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ upload.id}}</td> 
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ upload.title}}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    
                                    <Image :src="upload.image ? appUrl+upload.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class=" px-6 py-4 text-center">
                                    <div class="w-[300px] text-justify whitespace-pre-line ">
                                        <span v-if="upload.showFullDescription">
                                            {{ upload.description }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(upload)">
                                                Read Less
                                            </button>
                                        </span>
                                        <span v-else>
                                            {{ truncateDescription(upload.description) }}
                                            <button class="text-blue-700 underline ml-2" @click="toggleDescription(upload)">
                                                Read More
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">
                                    <a :href="upload.threeDLink" target="_blank" class="  ">
                                        <span class="text-blue-700"><i class="fab fa-google-drive"></i> </span>
                                        <span class="underline ml-2">Gdrive Link</span>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-center">{{ upload.instructor.fName}}, {{ upload.instructor.lName}}</td>
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-red-500 text-center">{{ upload.status}}</td>
                            
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class=" space-x-6" >
                                        <span class="pi pi-trash text-red-700 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Delete File'" @click="deleteConfirmation(upload.id)"></span>
                                                                                
                                        <span class="pi pi-pencil text-green-600 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Edit'" @click="handleEditFile(upload.id)" ></span>
                                    </div>
                                    
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div v-if="users.data.length && allUsersVisible" class="w-full flex justify-center mt-8 mb-8">
            <Pagination :links="users.links"/>    
        </div> -->
               <!--ALL PENDING UPLOADS-->
    </DashboardLayout>
    
</template>

<script setup>
import DashboardLayout from '../../../Layout/DashboardLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue'
import Pagination from '../../../AdminComponents/Pagination.vue';
import { toUpperFirst } from '../../../../Functions/Methods.vue';

const searchField = ref(null)
const user = usePage().props.user

const appUrl = ref('/storage/')
const data = defineProps({
    pending3d:Object,
})


const toggleDescription = (upload) => {
    upload.showFullDescription = !upload.showFullDescription;
};

const truncateDescription = (description) => {
    // Split the description into words
    const words = description.split(' ');

    // Take the first five words and join them back into a string
    return words.slice(0, 5).join(' ');
};

// Iterate through the data and add a property to track whether the full description is shown
data.pending3d.data.forEach(upload => {
    upload.showFullDescription = false;
});

</script>