<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">Approved 3D Page</span>
                </div>
                
                            
            </div>
        </div>
        
        <span class="text-red-500">
            TO DO:  IMPLEMENT SEARCH FIELD 
       </span>
       
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
                                
                               
                                <th scope="col" class="px-6 py-4 text-center">Approved by</th>
                                <th scope="col" class="px-6 py-4 text-center">Approval Date</th>
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
                                    
                                    <Image :src="approved3D.image ? appUrl+approved3D.image: appUrl+'Images/default.png'" alt="Image" width="60" preview>
                                        <template #indicator>
                                            <i class="pi pi-eye"></i>
                                        </template>
                                    </Image>     
                                </td>  
                                <td class=" px-6 py-4 text-center">
                                    <div class="w-[300px] text-justify whitespace-pre-line ">
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
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-red-500 text-center">do this</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-red-500 text-center">{{ approved3D.approved_at }}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div v-if="data.approved3D.data.length > 1"  class="w-full flex justify-center mt-8 mb-8">
                <Pagination :links="data.approved3D.links"/>    
            </div>
        </div>
    </DashboardLayout>
    
</template>

<script setup>
import DashboardLayout from '../../../Layout/DashboardLayout.vue';
import Pagination from '../../../AdminComponents/Pagination.vue';
import { usePage, Link } from '@inertiajs/vue3';
import {ref} from 'vue'
const user = usePage().props.user
const appUrl = ref('/storage/')
const data = defineProps({
    approved3D:Object,
})

const truncateDescription = (description) => {
    // Split the description into words
    const words = description.split(' ');

    // Take the first five words and join them back into a string
    return words.slice(0, 3).join(' ');
};

const toggleDescription = (approved3D) => {
    approved3D.showFullDescription = !approved3D.showFullDescription;
};

</script>