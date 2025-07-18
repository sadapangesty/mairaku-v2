<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Customers</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button class="btn btn-primary" @click="openAddModal()">Add Customer</button>
          <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
        </div>
      </div>
      <div class="datatable">
        <vue3-datatable
          :rows="rows"
          :columns="cols"
          :search="search"
          :totalRows="rows.length"
          :sortable="true"
          sortColumn="no"
          skin="bh-table-hover"
        >
          <template #address="data">
            <div class="whitespace-normal break-words">{{ data.value.address }}</div>
          </template>
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
              <button class="btn btn-sm btn-outline-danger p-1" @click="deleteCustomer(data.value)">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <FormModal
      v-if="showModal"
      :showModal="showModal"
      :isEditing="isEditing"
      :form="form"
      @close="closeModal"
      @save="saveCustomer"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import FormModal from './FormModal.vue';

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const rows = ref([]);

const form = ref({
  id_customer: null,
  name: '',
  email: '',
  phone: '',
  address: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'name', title: 'Nama', sortable: true },
  { field: 'email', title: 'Email', sortable: true },
  { field: 'phone', title: 'Telepon', sortable: true },
  { field: 'marking_code_prefix', title: 'Marking Code', sortable: true },
  { field: 'address', title: 'Alamat', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const loadCustomers = async () => {
  try {
    const res = await axios.get('/api/customers');
    rows.value = res.data.map((c, idx) => ({
      ...c,
      no: idx + 1
    }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data customers', 'error');
  }
};

onMounted(loadCustomers);

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_customer: null, name: '', email: '', phone: '', address: '' };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = {
    id_customer: data.id_customer,
    name: data.name,
    email: data.email,
    phone: data.phone,
    address: data.address,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveCustomer = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/customers/${form.value.id_customer}`, form.value);
      Swal.fire('Success', 'Customer updated!', 'success');
    } else {
      await axios.post('/api/customers', form.value);
      Swal.fire('Success', 'Customer added!', 'success');
    }
    await loadCustomers();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors
      ? Object.values(e.response.data.errors).flat().join('<br>')
      : 'Gagal';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteCustomer = async (data) => {
  if (!data.id_customer) return Swal.fire('Error', 'ID not found', 'error');
  const confirm = await Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/customers/${data.id_customer}`);
    Swal.fire('Deleted!', '', 'success');
    await loadCustomers();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to delete', 'error');
  }
};
</script>

<style>
td[data-col="address"] {
  white-space: normal !important;
  word-break: break-word;
}
</style>
