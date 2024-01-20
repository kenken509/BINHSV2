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
                        <InputText v-model="searchField" placeholder="search " />
                    </span>
                </div>                
            </div>
        </div>
        <div v-if="$page.props.flash.success" ><span class="p-3 text-gray-200">{{ successMessage($page.props.flash.success)  }}</span></div>
        <div v-if="$page.props.flash.error" class="flex items-center rounded-md bg-red-600 my-4 h-8 "><span class="p-3 text-gray-200">{{ $page.props.flash.error }}</span></div>

        <div  class="overflow-x-auto sm:-mx-6 lg:-mx-8  overflow-x">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="text-xs text-gray-200 uppercase bg-green-700  ">
                                <tr>
                                    <th scope="col" class="px-6 py-4">ID #</th>
                                    <th scope="col" class="px-6 py-4">Image</th>
                                    <th scope="col" class="px-6 py-4">Image</th>
                                    <th scope="col" class="px-6 py-4">Subject</th>
                                    <th scope="col" class="px-6 py-4">Submitted By</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                    <th scope="col" class="px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                v-for="user in currentPageItems"
                                :key="user.id"
                                
                                class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-gray-300">
                                    
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ user.id}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        
                                        <Image :src="user.image ? appUrl+user.image:'/storage/Images/default.png'" alt="Image" width="60" preview>
                                            <template #indicator>
                                                <i class="pi pi-eye"></i>
                                            </template>
                                        </Image>     
                                    </td>  
                                    
                                    <td class="whitespace-nowrap px-6 py-4"><span v-if="user.lName">{{ toUpperFirst(user.lName)  }}</span>, <span v-if="user.fName">{{ toUpperFirst(user.fName)  }}</span> <span v-if="user.mName">{{ user.mName.substring(0,1).toUpperCase() }}</span>.</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ user.email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ user.role }}</td>
                                

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class=" space-x-6" >
                                            <span class="pi pi-trash text-red-700 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Delete User Permanently'" @click="deleteConfirmation(user.id)"></span>
                                            <span class="pi pi-refresh text-green-600 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'Restore User'" @click="restoreConfirmation(user.id)" ></span>
                                            <span class="pi pi-eye text-green-600 scale-110 hover:dark:scale-150 cursor-pointer" v-tooltip.left="'View full info'" @click="openModal(user.id)" ></span>
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
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue'


const searchField = ref(null);

const user = usePage().props.user;
const data = defineProps({
    pending3d:Array,
})
</script>