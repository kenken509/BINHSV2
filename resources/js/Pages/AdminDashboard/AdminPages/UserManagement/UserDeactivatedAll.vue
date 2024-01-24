<template>
    <DashboardLayout :user="user">
        <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  ">
            <div class="">
                <span class="text-[20px] font-bold text-gray-500">Deactivated User Page</span>
            </div>
            
            
            <div class="py-4">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="searchField" placeholder="search " />
                </span>
            </div>
        </div>   
       
        
        <div v-if="$page.props.flash.success" class="flex items-center rounded-md bg-[#28a745] my-4 h-8 hidden "><span class="p-3 text-gray-200">{{ successMessage($page.props.flash.success) }}</span></div>
        <div v-if="$page.props.flash.error" class="flex items-center rounded-md bg-red-600 my-4 h-8 "><span class="p-3 text-gray-200">{{ $page.props.flash.error }}</span></div>
        
        <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <table  class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-200 uppercase bg-green-700  ">
                    <tr>
                        <th scope="col" class="text-center px-6 py-3">
                            ID#
                        </th>
                        <th scope="col" class="text-center px-6 py-3">
                            Name
                        </th>
                        
                        <th scope="col" class="text-center px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="text-center px-6 py-3">
                            Role
                        </th>
                        
                        
                        <th scope="col" class="text-center px-6 py-3">
                            Action
                        </th>
                        
                    </tr>
                </thead>
                <tbody v-for="user in currentPageItems" >
                    <tr class="bg-white border-b ">
                        <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 ">
                            {{ user.id }}
                        </td>
                        <td scope="row" class=" text-center px-6 py-4 font-medium text-gray-900  ">
                            {{ user.fName }}, {{ user.lName }}
                        </td>
                        <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 ">
                            {{ user.email }}
                        </td>
                        <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 ">
                            {{ user.role }} 
                        </td>
                        
                        <td scope="row" class=" text-center px-6 py-4 font-medium text-gray-900 ">
                            <div class="flex  space-x-10 md:justify-center text-center   items-center">
                                <div class="cursor-pointer hover:scale-125">
                                    <span class="pi pi-trash text-red-700 scale-150 hover:scale-150" v-tooltip.left="'Delete'" @click="deleteConfirmation(user.id)"></span>
                                </div>
                                
                                <div class="cursor-pointer hover:scale-125" v-tooltip.left="'Reactivate'">
                                    <span class="pi pi-refresh text-green-600 scale-150 hover:scale-150" @click="reactivateConfirmation(user.id)"></span>
                                </div>
                                
                            </div>   
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            
        </div>
        <div class="flex justify-center w-full space-x-4 mt-4" v-if="totalPages > 1">
            <div @click="prevPage" class="flex items-center  cursor-pointer hover:text-red-400">
                <i class="pi pi-angle-double-left cursor-pointer" style="font-size: 24px;"></i>
                
            </div>
            <div class="flex space-x-2">
                <div v-for="(number, index) in totalPages" class="hover:text-green-500 cursor-pointer" @click="changePageClick(index+1)" >
                    <div v-if="currentPage === index+1" class="border bg-green-700 px-2 rounded-lg text-gray-200" >{{ index+1 }}</div>
                    <div v-else class="px-2">{{ index+1 }}</div>
                </div>
            </div>
            <div @click="nextPage" class="flex items-center  cursor-pointer hover:text-green-400">
                <i class="pi pi-angle-double-right  " style="font-size: 24px;"></i>
            </div>
        </div>
    </DashboardLayout>

</template>

<script setup>
import DashboardLayout from '../../../AdminDashboard/Layout/DashboardLayout.vue'
import {Link, useForm, usePage, router} from '@inertiajs/vue3'
import {ref, onMounted, computed, watch} from 'vue'
import { toUpperFirst } from '../../../Functions/Methods.vue';
import Swal from 'sweetalert2';

const user = usePage().props.user;


const searchField = ref(null);

const data = defineProps({
    deactivatedUsers:Array,
})

const filteredData = ref([]);

onMounted(()=>{
    
    filteredData.value = data.deactivatedUsers
    pageNumbers.length = totalPages.value;
})

watch(() => data.deactivatedUsers, (newData) => {
  filteredData.value = newData;
});

// search logic
watch(searchField,(val)=>{
   
    if(val === '')
    {
        filteredData.value = data.deactivatedUsers
    }
    else
    {
        updateFilteredUsers();
    }
})

function updateFilteredUsers(){
    console.log('ito laman: '+searchField.value);
    if(searchField.value === '')
    {
        filteredData.value = "";
       
    }
    else
    {
        //allUsersVisible.value = false
        filteredData.value = data.deactivatedUsers.filter(user =>
        Object.values(user).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(searchField.value.toLowerCase())
            )
        );
    }
    
}

//pagination logic
const itemsPerPage = ref(10);
const currentPage = ref(1);


const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage.value));
const pageNumbers = ref([]);
watch(currentPage,(val)=>{
    console.log(val);
})
const currentPageItems = computed(() => {
    
  const start = (currentPage.value - 1) * itemsPerPage.value; 
  const end = start + itemsPerPage.value;
  
  return filteredData.value.slice(start, end);
}); 

const nextPage = () => {
    
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};


const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const changePageClick = (index)=>
{
    currentPage.value = index;
}

// alert codes
function deleteConfirmation(userId)
{
    Swal.fire({
        title:'Delete confirmation',
        text:"Do you want to delete this user?",
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
            const userDeleteUrl = route('admin.deactivatedUserDelete', {id: userId});
            
            router.delete(userDeleteUrl);
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

function reactivateConfirmation(userId)
{
    Swal.fire({
        title:'Reactivation confirmation',
        text:"Do you want to reactivate this user?",
        icon:'warning',
        confirmButtonText:'Yes',
        cancelButtonText:'Cancel',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        allowOutsideClick:false,
        allowEscapeKey:false,
    }).then((result)=>{
        if(result.isConfirmed)
        {
            const userReactivationUrl = route('admin.reactivateUser', {id: userId});
            
            router.patch(userReactivationUrl);
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




</script>