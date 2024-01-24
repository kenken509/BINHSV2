<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">All Archived Users</span>
                </div>
                
                <div class="">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="searchField" placeholder="search " />
                    </span>
                </div>
                
            </div>
            <div v-if="$page.props.flash.success" class="hidden" ><span class="p-3 text-gray-200">{{ successMessage($page.props.flash.success) }}</span></div>
                <div v-if="$page.props.flash.error" class="flex items-center rounded-md bg-red-600 my-4 h-8 hidden "><span class="p-3 text-gray-200">{{ errorMessage($page.props.flash.error)  }}</span></div>
            <div  class="overflow-x-auto sm:-mx-6 lg:-mx-8  overflow-x">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="text-xs text-gray-200 uppercase bg-green-700  ">
                                <tr>
                                    <th scope="col" class="px-6 py-4">ID #</th>
                                    <th scope="col" class="px-6 py-4">Picture</th>
                                    <th scope="col" class="px-6 py-4">Full name</th>
                                    <th scope="col" class="px-6 py-4">Email</th>
                                    <th scope="col" class="px-6 py-4">Role</th>
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
        </div>
    </DashboardLayout>
    
</template>

<script setup>
import DashboardLayout from '../../Layout/DashboardLayout.vue';
import {Link, useForm, usePage, router} from '@inertiajs/vue3'
import  {onMounted, ref, watch,computed } from 'vue'
import { toUpperFirst } from '../../../Functions/Methods.vue';
import Swal from 'sweetalert2';


const user = usePage().props.user;
const searchField = ref(null);


const data = defineProps({
    archivedUsers:Array,
})
const filteredData = ref([])

onMounted(()=>{
   
    filteredData.value = data.archivedUsers
})


// searcch logic
watch(searchField,(val)=>{
   
   if(val === '')
   {
       filteredData.value = data.archivedUsers
   }
   else
   {
       updateFilteredUsers();
   }
})

function updateFilteredUsers(){
    //console.log('ito laman: '+searchField.value);
    if(searchField.value === '')
    {
        filteredData.value = "";
       
    }
    else
    {
        filteredData.value = data.archivedUsers.filter(user =>
        Object.values(user).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(searchField.value.toLowerCase())
            )
        );
    }
    
}

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
            const userDeleteUrl = route('admin.userDeletePermanently', {id: userId});

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

function restoreConfirmation(userId)
{
    Swal.fire({
        title:'Restoration confirmation',
        text:"Are you sure you want to restore this user?",
        icon:'warning',
        confirmButtonText:'Yes, restore it!',
        cancelButtonText:'Cancel',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        allowOutsideClick:false,
        allowEscapeKey:false,
    }).then((result)=>{
        if(result.isConfirmed)
        {
            const userRestoreUrl = route('admin.restoreUser', {id: userId});

            router.patch(userRestoreUrl);
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
    }).then((val)=>{
        if(val.isConfirmed)
        {
            location.reload();
        }
    })
}

function errorMessage(message) {
  Swal.fire({
    icon: "error",
    title: "Oops...",
    text: message + '!',
    allowOutsideClick:false,
  }).then((result) => {
    if (result.isConfirmed) {
        location.reload();
    }
  })
}

</script>