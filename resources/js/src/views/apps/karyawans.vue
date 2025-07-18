<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Karyawan</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary" @click="openAddModal()">
            Add Karyawan
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
        <template #alamat="data">
          <div class="whitespace-normal break-words">
            {{ data.value.alamat }}
          </div>
        </template>

          <template #tanggal_masuk="data">
            {{ data.value.tanggal_masuk }}
          </template>

          <template #actions="data">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">
                ✏️
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteKaryawan(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Karyawan' : 'Tambah Karyawan' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama" type="text" placeholder="Nama" class="form-input" />
          <input v-model="form.email" type="email" placeholder="Email" class="form-input" />
          <input v-model="form.telepon" type="text" placeholder="Telepon" class="form-input" />
          <textarea v-model="form.alamat" placeholder="Alamat" class="form-input" rows="3"></textarea>
          <input v-model="form.jabatan" type="text" placeholder="Jabatan" class="form-input" />
          <input v-model="form.tanggalMasuk" type="date" class="form-input" />
          <input v-model="form.salary" type="number" placeholder="Salary" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveKaryawan">{{ isEditing ? 'Update' : 'Save' }}</button>
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
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Karyawan' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_karyawan: null as number | null,
  nama: '',
  email: '',
  telepon: '',
  alamat: '',
  jabatan: '',
  tanggalMasuk: '',
  salary: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama', title: 'Nama', sortable: true },
  { field: 'email', title: 'Email', sortable: true },
  { field: 'telepon', title: 'Telepon', sortable: true },
  { field: 'alamat', title: 'Alamat', sortable: true },
  { field: 'jabatan', title: 'Jabatan', sortable: true },
  { field: 'tanggal_masuk', title: 'Tanggal Masuk', sortable: true },
  { field: 'salary', title: 'Salary', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);

const loadKaryawan = async () => {
  try {
    const response = await axios.get('/api/karyawans');
    rows.value = response.data.map((item: any, index: number) => ({
      ...item,
      no: index + 1,
    }));
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Gagal memuat data karyawan.', 'error');
  }
};

onMounted(() => loadKaryawan());

const openAddModal = () => {
  isEditing.value = false;
  form.value = {
    id_karyawan: null,
    nama: '',
    email: '',
    telepon: '',
    alamat: '',
    jabatan: '',
    tanggalMasuk: '',
    salary: '',
  };
  showModal.value = true;
};

const openEditModal = (karyawan: any) => {
  isEditing.value = true;
  form.value = {
    id_karyawan: karyawan.id_karyawan,
    nama: karyawan.nama,
    email: karyawan.email,
    telepon: karyawan.telepon,
    alamat: karyawan.alamat,
    jabatan: karyawan.jabatan,
    tanggalMasuk: karyawan.tanggal_masuk,
    salary: karyawan.salary,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveKaryawan = async () => {
  loading.value = true;
  try {
    const payload = {
      ...form.value,
      salary: parseFloat(form.value.salary as string),
    };
    if (isEditing.value) {
      await axios.put(`/api/karyawans/${form.value.id_karyawan}`, payload);
      Swal.fire('Success', 'Data karyawan diperbarui!', 'success');
    } else {
      await axios.post('/api/karyawans', payload);
      Swal.fire('Success', 'Karyawan baru ditambahkan!', 'success');
    }
    await loadKaryawan();
    closeModal();
  } catch (error: any) {
    console.error(error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan data';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteKaryawan = async (karyawan: any) => {
  if (!karyawan.id_karyawan) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirmResult = await Swal.fire({
    title: `Hapus karyawan "${karyawan.nama}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirmResult.isConfirmed) return;
  loading.value = true;
  try {
    await axios.delete(`/api/karyawans/${karyawan.id_karyawan}`);
    Swal.fire('Deleted!', 'Karyawan berhasil dihapus.', 'success');
    await loadKaryawan();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus karyawan.', 'error');
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr || typeof dateStr !== 'string') return '-';
  const trimmed = dateStr.trim();
  if (!trimmed) return '-';
  const d = new Date(trimmed);
  if (isNaN(d.getTime())) return '-';

  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();

  return `${year}/${month}/${day}`;
};


</script>

