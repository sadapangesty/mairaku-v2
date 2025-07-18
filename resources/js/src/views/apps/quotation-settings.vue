<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Quotation Settings</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button class="btn btn-primary" @click="openAddModal()">Add Setting</button>
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
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
              <button class="btn btn-sm btn-outline-danger p-1" @click="deleteSetting(data.value)">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Setting' : 'Add Setting' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.asuransi_percent" type="number" placeholder="Asuransi (%)" class="form-input" />
          <input v-model="form.margin_percent" type="number" placeholder="Margin (%)" class="form-input" />
          <input v-model="form.biaya_pengiriman_laut_percent" type="number" placeholder="Biaya Pengiriman Laut (%)" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveSetting">{{ isEditing ? 'Update' : 'Save' }}</button>
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
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Quotation Settings' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const rows = ref([]);

const form = ref({
  id: null,
  asuransi_percent: '',
  margin_percent: '',
  biaya_pengiriman_laut_percent: ''
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'asuransi_percent', title: 'Asuransi (%)', sortable: true },
  { field: 'margin_percent', title: 'Margin (%)', sortable: true },
  { field: 'biaya_pengiriman_laut_percent', title: 'Biaya Pengiriman Laut (%)', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const loadSettings = async () => {
  try {
    const res = await axios.get('/api/quotation-settings');
    rows.value = res.data.map((c, idx) => ({ ...c, no: idx + 1 }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to load data', 'error');
  }
};

onMounted(loadSettings);

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id: null, asuransi_percent: '', margin_percent: '', biaya_pengiriman_laut_percent: '' };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = {
    id: data.id,
    asuransi_percent: data.asuransi_percent,
    margin_percent: data.margin_percent,
    biaya_pengiriman_laut_percent: data.biaya_pengiriman_laut_percent
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveSetting = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/quotation-settings/${form.value.id}`, form.value);
      Swal.fire('Success', 'Updated!', 'success');
    } else {
      await axios.post('/api/quotation-settings', form.value);
      Swal.fire('Success', 'Added!', 'success');
    }
    await loadSettings();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors
      ? Object.values(e.response.data.errors).flat().join('<br>')
      : 'Failed';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteSetting = async (data) => {
  const confirm = await Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/quotation-settings/${data.id}`);
    Swal.fire('Deleted!', '', 'success');
    await loadSettings();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to delete', 'error');
  }
};
</script>
