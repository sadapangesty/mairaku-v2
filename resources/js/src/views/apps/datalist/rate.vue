<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Rate Mata Uang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary w-full sm:flex-1" @click="openAddModal()">
            Add Rate
          </button>
          <input v-model="search" type="text" class="form-input w-full sm:flex-1" placeholder="Search..." />
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
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">
                ✏️
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteRate(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Rate' : 'Add Rate' }}</h3>
        <div class="grid gap-4">
          <select v-model="form.from_currency_id" class="form-input">
            <option disabled value="">From Currency</option>
            <option v-for="c in currencies" :key="c.id_currency" :value="c.id_currency">{{ c.kode }}</option>
          </select>
          <select v-model="form.to_currency_id" class="form-input">
            <option disabled value="">To Currency</option>
            <option v-for="c in currencies" :key="c.id_currency" :value="c.id_currency">{{ c.kode }}</option>
          </select>
          <select v-model="form.rate_type" class="form-input">
            <option disabled value="">Rate Type</option>
            <option v-for="t in rateTypes" :key="t">{{ t }}</option>
          </select>
          <select v-model="form.rate_category" class="form-input">
            <option disabled value="">Rate Category</option>
            <option v-for="c in rateCategories" :key="c">{{ c }}</option>
          </select>
          <input v-model="form.rate" type="number" placeholder="Rate" class="form-input" />
          <input v-model="form.effective_date" type="date" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveRate">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axios from 'axios';
import Swal from 'sweetalert2';

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_currency_rate: null as number | null,
  from_currency_id: '',
  to_currency_id: '',
  rate_category: '',
  rate_type: '',
  rate: '',
  effective_date: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'from_currency', title: 'From', sortable: true },
  { field: 'to_currency', title: 'To', sortable: true },
  { field: 'rate_category', title: 'Category', sortable: true },
  { field: 'rate_type', title: 'Type', sortable: true },
  { field: 'rate', title: 'Rate', sortable: true },
  { field: 'effective_date', title: 'Date', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);
const currencies = ref<any[]>([]);
const rateTypes = ref<string[]>([]);
const rateCategories = ref<string[]>([]);

const loadRates = async () => {
  try {
    const res = await axios.get('/api/currency-rates');
    rows.value = res.data.map((item: any, index: number) => ({
      ...item,
      no: index + 1,
      from_currency: item.from_currency.kode,
      to_currency: item.to_currency.kode,
    }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to load rates', 'error');
  }
};

const loadDropdownData = async () => {
  try {
    const res = await axios.get('/api/currency-rates/dropdown-data');
    currencies.value = res.data.currencies;
    rateTypes.value = res.data.rate_types;
    rateCategories.value = res.data.rate_categories;
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to load dropdown data', 'error');
  }
};

onMounted(() => {
  loadRates();
  loadDropdownData();
});

const openAddModal = () => {
  isEditing.value = false;
  form.value = {
    id_currency_rate: null,
    from_currency_id: '',
    to_currency_id: '',
    rate_category: '',
    rate_type: '',
    rate: '',
    effective_date: '',
  };
  showModal.value = true;
};

const openEditModal = (rate: any) => {
  isEditing.value = true;
  form.value = {
    id_currency_rate: rate.id_currency_rate,
    from_currency_id: rate.from_currency_id,
    to_currency_id: rate.to_currency_id,
    rate_category: rate.rate_category,
    rate_type: rate.rate_type,
    rate: rate.rate,
    effective_date: rate.effective_date,
  };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveRate = async () => {
  loading.value = true;
  try {
    const payload = { ...form.value };
    if (isEditing.value) {
      await axios.put(`/api/currency-rates/${form.value.id_currency_rate}`, payload);
      Swal.fire('Success', 'Rate updated!', 'success');
    } else {
      await axios.post('/api/currency-rates', payload);
      Swal.fire('Success', 'Rate added!', 'success');
    }
    await loadRates();
    closeModal();
  } catch (error: any) {
    console.error(error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Failed to save rate';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteRate = async (rate: any) => {
  if (!rate.id_currency_rate) return Swal.fire('Error', 'ID not found', 'error');
  const confirm = await Swal.fire({
    title: `Delete rate?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete!',
    cancelButtonText: 'Cancel',
  });
  if (!confirm.isConfirmed) return;
  loading.value = true;
  try {
    await axios.delete(`/api/currency-rates/${rate.id_currency_rate}`);
    Swal.fire('Deleted!', 'Rate deleted.', 'success');
    await loadRates();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to delete rate.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>
