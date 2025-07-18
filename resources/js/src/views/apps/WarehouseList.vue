<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Gudang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary" @click="openAddModal()">
            Add Gudang
          </button>
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
            <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
            <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteWarehouse(data.value)">❌</button>
          </div>
        </template>
      </vue3-datatable>
    </div>
      </div>


    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Gudang' : 'Tambah Gudang' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.code" type="text" placeholder="Code" class="form-input" />
          <input v-model="form.name" type="text" placeholder="Name" class="form-input" />
          <input v-model="form.country" type="text" placeholder="Country" class="form-input" />
          <input v-model="form.city" type="text" placeholder="City" class="form-input" />
          <input v-model="form.phone" type="text" placeholder="Phone" class="form-input" />
          <textarea v-model="form.address" placeholder="Address" class="form-input" rows="2"></textarea>
          <select v-model="form.currency_id" class="form-input">
            <option disabled value="">Select Currency</option>
            <option v-for="c in currencies" :key="c.id_currency" :value="c.id_currency">{{ c.kode }}</option>
          </select>
          <textarea v-model="form.note" placeholder="Note" class="form-input" rows="2"></textarea>
          <label><input type="checkbox" v-model="form.is_active" /> Active</label>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveWarehouse">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Gudang' });


const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const currencies = ref<any[]>([]);
const rows = ref<any[]>([]);

const form = ref({
  id: null as number | null,
  code: '',
  name: '',
  country: '',
  city: '',
  phone: '',
  address: '',
  currency_id: null,
  note: '',
  is_active: true,
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'code', title: 'Code', sortable: true },
  { field: 'name', title: 'Name', sortable: true },
  { field: 'country', title: 'Country', sortable: true },
  { field: 'city', title: 'City', sortable: true },
  { field: 'phone', title: 'Phone', sortable: true },
  { field: 'currency_name', title: 'Currency', sortable: true },
  { field: 'is_active_text', title: 'Status', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const loadWarehouses = async () => {
  try {
    const res = await axios.get('/api/warehouses');
    rows.value = res.data.map((w: any, i: number) => ({
      ...w,
      no: i + 1,
      currency_name: w.currency?.kode ?? '-',
      is_active_text: w.is_active ? 'Active' : 'Inactive',
    }));
  } catch (e) {
    Swal.fire('Error', 'Gagal memuat data gudang.', 'error');
  }
};

const loadDropdownData = async () => {
  try {
    const res = await axios.get('/api/warehouses/dropdown-data');
    currencies.value = res.data.currencies;
  } catch (e) {
    Swal.fire('Error', 'Failed to load dropdown.', 'error');
  }
};

onMounted(() => {
  loadWarehouses();
  loadDropdownData();
});

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id: null, code: '', name: '', country: '', city: '', phone: '', address: '', currency_id: null, note: '', is_active: true };
  showModal.value = true;
};

const openEditModal = (w: any) => {
  isEditing.value = true;
  form.value = { id: w.id, code: w.code, name: w.name, country: w.country, city: w.city, phone: w.phone, address: w.address, currency_id: w.currency_id, note: w.note, is_active: w.is_active };
  showModal.value = true;
};

const closeModal = () => showModal.value = false;

const saveWarehouse = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/warehouses/${form.value.id}`, form.value);
      Swal.fire('Success', 'Updated!', 'success');
    } else {
      await axios.post('/api/warehouses', form.value);
      Swal.fire('Success', 'Added!', 'success');
    }
    await loadWarehouses();
    closeModal();
  } catch (e: any) {
    const msg = e.response?.data?.errors ? Object.values(e.response.data.errors).flat().join('<br>') : 'Failed to save.';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteWarehouse = async (w: any) => {
  const confirm = await Swal.fire({ title: `Delete "${w.name}"?`, icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/warehouses/${w.id}`);
    Swal.fire('Deleted!', '', 'success');
    await loadWarehouses();
  } catch {
    Swal.fire('Error', 'Failed to delete.', 'error');
  }
};
</script>
