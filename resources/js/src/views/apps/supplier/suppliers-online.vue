<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Supplier Online</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary" @click="openAddModal()">Add Supplier</button>
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
          skin="whitespace-nowrap bh-table-hover"
        >
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">✏️</button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteSupplier(data.value)" title="Delete">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Supplier Online' : 'Tambah Supplier Online' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.name" type="text" placeholder="Nama" class="form-input" />
          <input v-model="form.negara" type="text" placeholder="Negara" class="form-input" />
          <input v-model="form.situs" type="text" placeholder="Situs (contoh: taobao, tokopedia)" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveSupplier">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_supplier: null,
  name: '',
  negara: '',
  situs: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'name', title: 'Nama', sortable: true },
  { field: 'negara', title: 'Negara', sortable: true },
  { field: 'situs', title: 'Situs', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref([]);

const loadSuppliers = async () => {
  try {
    const res = await axios.get('/api/suppliers-online');
    rows.value = res.data.map((item, idx) => ({ ...item, no: idx + 1 }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data supplier online', 'error');
  }
};

onMounted(loadSuppliers);

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_supplier: null, name: '', negara: '', situs: '' };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = { ...data };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveSupplier = async () => {
  loading.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/suppliers-online/${form.value.id_supplier}`, form.value);
      Swal.fire('Success', 'Supplier online updated!', 'success');
    } else {
      await axios.post('/api/suppliers-online', form.value);
      Swal.fire('Success', 'Supplier online added!', 'success');
    }
    await loadSuppliers();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors ? Object.values(e.response.data.errors).flat().join('<br>') : 'Failed';
    Swal.fire('Error', msg, 'error');
  } finally { loading.value = false; }
};

const deleteSupplier = async (data) => {
  if (!data.id_supplier) return Swal.fire('Error', 'ID not found', 'error');
  const confirm = await Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/suppliers-online/${data.id_supplier}`);
    Swal.fire('Deleted!', '', 'success');
    await loadSuppliers();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to delete', 'error');
  }
};
</script>
