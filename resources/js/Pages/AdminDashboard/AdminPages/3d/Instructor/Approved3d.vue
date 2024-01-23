<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">Approved 3D Page</span>
                </div>
                <div class="">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="searchField" placeholder="search "   id="searchFieldid"/>
                </span>
            </div>
                            
            </div>
        </div>
        
       <div v-if="filteredData"  class="overflow-x-auto sm:-mx-6 lg:-mx-8 mt-4 overflow-x">
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
                                <th scope="col" class="px-6 py-4 text-center">Approved Date</th>
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
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium  text-center">Admin {{ approved3D.approver.lName }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium  text-center">{{ formatDate(approved3D.approved_date)  }}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
       </div>
       <div v-else class="overflow-x-auto sm:-mx-6 lg:-mx-8 mt-4 overflow-x">
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
                                <th scope="col" class="px-6 py-4 text-center">Approved Date</th>
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
                                
                                <td class="whitespace-nowrap px-6 py-4 font-medium  text-center">Admin {{ approved3D.approver.lName }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium  text-center">{{ formatDate(approved3D.approved_date)  }}</td>
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
import {ref, watch} from 'vue'
const user = usePage().props.user
const appUrl = ref('/storage/')
const data = defineProps({
    approved3D:Object,
    searchApproved3D:Array,
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

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};



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

    filteredData.value = data.searchApproved3D.filter(searchApproved3D => {
        const isInstructorMatch = (
            searchApproved3D.instructor.fName.toLowerCase().includes(searchTerm) ||
            searchApproved3D.instructor.lName.toLowerCase().includes(searchTerm)
        );

        const isSubjectMatch = searchApproved3D.instructor.subject.name.toLowerCase().includes(searchTerm);

        const isStatusMatch = searchApproved3D.status.toLowerCase().includes(searchTerm);

        const isDescriptionMatch = searchApproved3D.description.toLowerCase().includes(searchTerm);

        const isApproverMatch = (
            searchApproved3D.approver.fName.toLowerCase().includes(searchTerm) ||
            searchApproved3D.approver.lName.toLowerCase().includes(searchTerm)
        );

        const isFormattedDateMatch = (
            formatDate(searchApproved3D.approved_date).toLowerCase().includes(searchTerm)
        );

        const isOriginalDateMatch = (
            searchApproved3D.approved_date.toLowerCase().includes(searchTerm)
        );

        return (
            searchApproved3D.title.toLowerCase().includes(searchTerm) ||
            isStatusMatch ||
            isDescriptionMatch ||
            isInstructorMatch ||
            isSubjectMatch ||
            isApproverMatch ||
            isFormattedDateMatch ||
            isOriginalDateMatch
        );
    });
}
</script>