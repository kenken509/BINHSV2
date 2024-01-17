<template>
    <DashboardLayout :user="user">
        <div class="flex flex-col ">
            <div class="flex justify-between items-center justify-between border-bot-only border-gray-600  py-2">
                <div class="">
                    <span class="text-[20px] font-bold text-gray-500">Deactivated Users Page</span>
                </div>
                
                <div class="">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <!-- <InputText v-model="searchField" placeholder="search " /> -->
                    </span>
                </div>
                <!-- <div v-if="user.role === 'admin'" class="col-span-6 md:col-span-1 item-center">
                    <form @submit.prevent="submit">
                        <Dropdown  v-model="selectedRole" :options="filter" optionLabel="role" placeholder="Sort by role" class="w-full md:w-14rem " />
                    </form> 
                </div> -->
            </div>
            
        </div>
        
    </DashboardLayout>

</template>

<script setup>
import DashboardLayout from '../../../AdminDashboard/Layout/DashboardLayout.vue'
import {Link, useForm, usePage, router} from '@inertiajs/vue3'
import {ref, onMounted} from 'vue'
import { toUpperFirst } from '../../../Functions/Methods.vue';

const user = usePage().props.user;

const allUsersVisible = ref(false)
const data = defineProps({
    deactivatedUsers:Array,
})

const filteredUsers = ref(data.deactivatedUsers);

onMounted(()=>{
    allUsersVisible.value = true
})
</script>