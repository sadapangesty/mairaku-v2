<template>
    <div>
        <div class="panel px-0 pb-1.5 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="datatable quotation-table">
                <div class="mb-4.5 px-5 flex md:items-center md:flex-row flex-col gap-5">
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-danger gap-2" @click="deleteRow()">
                            <!-- svg delete icon -->
                            Delete
                        </button>
                        <router-link to="/apps/quatation/add" class="btn btn-primary gap-2">
                            <!-- svg plus icon -->
                            Add New Quotation
                        </router-link>
                    </div>
                    <div class="ltr:ml-auto rtl:mr-auto">
                        <input v-model="search" type="text" class="form-input" placeholder="Search..." />
                    </div>
                </div>

                <vue3-datatable
                    ref="datatable"
                    :rows="items"
                    :columns="cols"
                    :totalRows="items?.length"
                    :hasCheckbox="true"
                    :sortable="true"
                    :search="search"
                    skin="whitespace-nowrap bh-table-hover"
                >
                    <template #quotation="data">
                        <router-link to="/apps/quotation/preview" class="text-primary underline font-semibold hover:no-underline">
                            #{{ data.value.quotation_no }}
                        </router-link>
                    </template>
                    <template #customer="data">
                        <div class="flex items-center font-semibold">
                            <div class="p-0.5 bg-white-dark/30 rounded-full w-max ltr:mr-2 rtl:ml-2">
                                <img class="h-8 w-8 rounded-full object-cover" :src="`/assets/images/profile-${data.value.id}.jpeg`" />
                            </div>
                            {{ data.value.customer }}
                        </div>
                    </template>
                    <template #total="data">
                        <div class="font-semibold ltr:text-right rtl:text-left">¥{{ data.value.total }}</div>
                    </template>
                    <template #status="data">
                        <span class="badge" :class="[data.value.status === 'Approved' ? 'badge-outline-success' : 'badge-outline-warning']">{{ data.value.status }}</span>
                    </template>
                    <template #actions="data">
                      <div class="flex gap-4 items-center justify-center">
                          <router-link :to="`/apps/quotation/edit/${data.value.id}`" class="hover:text-info">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                  <path opacity="0.5" d="M22 10.5V12C22 16.714 22 19.0711
                                  20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22
                                  4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2
                                  7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595
                                  2 12 2H13.5" stroke="currentColor" stroke-width="1.5"
                                  stroke-linecap="round"></path>
                                  <path d="M17.3009 2.80624L16.652 3.45506L10.6872
                                  9.41993C10.2832 9.82394 10.0812 10.0259 9.90743
                                  10.2487C9.70249 10.5114 9.52679 10.7957 9.38344
                                  11.0965C9.26191 11.3515 9.17157 11.6225 8.99089
                                  12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897
                                  8.01862 15.5837 8.21744 15.7826C8.41626 15.9814
                                  8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354
                                  15.0091C12.3775 14.8284 12.6485 14.7381 12.9035
                                  14.6166C13.2043 14.4732 13.4886 14.2975 13.7513
                                  14.0926C13.9741 13.9188 14.1761 13.7168 14.5801
                                  13.3128L20.5449 7.34795L21.1938 6.69914C22.2687
                                  5.62415 22.2687 3.88124 21.1938 2.80624C20.1188
                                  1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                  stroke="currentColor" stroke-width="1.5"></path>
                              </svg>
                          </router-link>

                          <router-link :to="`/apps/quotation/preview/${data.value.id}`" class="hover:text-primary">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                  <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915
                                  2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489
                                  8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4
                                  19.028 6.49956 20.7251 8.70433C21.575 9.80853 22
                                  10.3606 22 12C22 13.6394 21.575 14.1915 20.7251
                                  15.2957C19.028 17.5004 16.1819 20 12 20C7.81811
                                  20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor"
                                  stroke-width="1.5"></path>
                                  <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431
                                  15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569
                                  9 15 10.3431 15 12Z" stroke="currentColor"
                                  stroke-width="1.5"></path>
                              </svg>
                          </router-link>

                          <button type="button" class="hover:text-danger" @click="deleteRow(`${data.value.id}`)">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                  <path d="M20.5001 6H3.5" stroke="currentColor"
                                  stroke-width="1.5" stroke-linecap="round"></path>
                                  <path d="M18.8334 8.5L18.3735 15.3991C18.1965
                                  18.054 18.108 19.3815 17.243 20.1907C16.378
                                  21 15.0476 21 12.3868 21H11.6134C8.9526 21
                                  7.6222 21 6.75719 20.1907C5.89218 19.3815
                                  5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                  stroke="currentColor" stroke-width="1.5"
                                  stroke-linecap="round"></path>
                                  <path opacity="0.5" d="M9.5 11L10 16"
                                  stroke="currentColor" stroke-width="1.5"
                                  stroke-linecap="round"></path>
                                  <path opacity="0.5" d="M14.5 11L14 16"
                                  stroke="currentColor" stroke-width="1.5"
                                  stroke-linecap="round"></path>
                              </svg>
                          </button>
                      </div>
                  </template>

                </vue3-datatable>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useMeta } from '@/composables/use-meta';
useMeta({ title: 'Quotation List' });

const datatable: any = ref(null);
const search = ref('');
const cols = ref([
    { field: 'quotation', title: 'Quotation No' },
    { field: 'customer', title: 'Customer' },
    { field: 'date', title: 'Date' },
    { field: 'total', title: 'Total', headerClass: 'justify-end' },
    { field: 'status', title: 'Status' },
    { field: 'actions', title: 'Actions', sort: false, headerClass: 'justify-center' },
]);

const items = ref([
    {
        id: 1,
        quotation_no: 'Q-2025001',
        customer: 'Bayu Aji',
        date: '2025-07-08',
        total: '2000',
        status: 'Approved',
    },
    {
        id: 2,
        quotation_no: 'Q-2025002',
        customer: 'Andi Wijaya',
        date: '2025-07-09',
        total: '3500',
        status: 'Pending',
    },
    {
        id: 3,
        quotation_no: 'Q-2025003',
        customer: 'Siti Aminah',
        date: '2025-07-10',
        total: '1500',
        status: 'Approved',
    },
    // ... tambahkan data dummy lainnya sesuai kebutuhan
]);

const deleteRow = (item: any = null) => {
    if (confirm('Are you sure want to delete selected row ?')) {
        if (item) {
            items.value = items.value.filter((d) => d.id != item);
            datatable.value.clearSelectedRows();
        } else {
            let selectedRows = datatable.value.getSelectedRows();
            const ids = selectedRows.map((d) => d.id);
            items.value = items.value.filter((d) => !ids.includes(d.id as never));
            datatable.value.clearSelectedRows();
        }
    }
};
</script>
