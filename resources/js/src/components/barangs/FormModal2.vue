<template>
  <div
    v-if="showModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
  >
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
      <h3 class="text-lg font-semibold mb-4">
        {{ isEditing ? 'Edit Barang' : 'Tambah Barang' }}
      </h3>

      <div class="grid gap-4">
        <input v-model="localForm.nama_barang" type="text" placeholder="Nama Barang" class="form-input" />

        <select v-model="localForm.id_kategori" class="form-input">
          <option disabled value="">Pilih Kategori</option>
          <option v-for="item in kategoris" :key="item.id_kategori" :value="item.id_kategori">
            {{ item.nama }}
          </option>
        </select>

        <select v-model="localForm.id_jenis" class="form-input">
          <option disabled value="">Pilih Jenis</option>
          <option v-for="item in jeniss" :key="item.id_jenis" :value="item.id_jenis">
            {{ item.nama }}
          </option>
        </select>

        <input v-model="localForm.sku" type="text" placeholder="SKU" class="form-input" />

        <input v-model.number="localForm.harga" type="number" placeholder="Harga" class="form-input" />

        <input v-model="localForm.link_barang" type="text" placeholder="Link Barang" class="form-input" />

        <input type="file" accept="image/*" class="form-input" @change="onImageChange" />

        <div v-if="imagePreview" class="mt-2">
          <img :src="imagePreview" class="w-20 h-20 object-cover rounded-full" />
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
        <button class="btn btn-primary" @click="handleSaveBarang">
          {{ isEditing ? 'Update' : 'Save' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

// --- Interfaces ---
interface Kategori {
  id_kategori: string
  nama: string
}

interface Jenis {
  id_jenis: string
  nama: string
}

interface FormBarang {
  nama_barang: string
  id_kategori: string
  id_jenis: string
  sku: string
  harga: number
  link_barang: string
}

// --- Props and Emits ---
const props = defineProps<{
  showModal: boolean
  isEditing: boolean
  form: FormBarang
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', form: FormBarang): void
}>()

const kategoris = ref<Kategori[]>([])
const jeniss = ref<Jenis[]>([])
const imagePreview = ref<string | null>(null)
const imageFile = ref<File | null>(null)

const defaultForm: FormBarang = {
  nama_barang: '',
  id_kategori: '',
  id_jenis: '',
  sku: '',
  harga: 0,
  link_barang: ''
}

const localForm = ref<FormBarang>({ ...defaultForm, ...props.form })

watch(
  () => props.showModal,
  async (val) => {
    if (val) {
      localForm.value = { ...defaultForm, ...props.form }
      await fetchKategoris()
      await fetchJeniss()
    }
  }
)

onMounted(async () => {
  if (props.showModal) {
    await fetchKategoris()
    await fetchJeniss()
  }
})

const fetchKategoris = async () => {
  if (kategoris.value.length > 0) return
  try {
    const res = await axios.get('/api/kategoris')
    kategoris.value = res.data
  } catch (err) {
    console.error('Gagal mengambil data kategori:', err)
  }
}

const fetchJeniss = async () => {
  if (jeniss.value.length > 0) return
  try {
    const res = await axios.get('/api/jenis')
    jeniss.value = res.data
  } catch (err) {
    console.error('Gagal mengambil data jenis:', err)
  }
}

const onImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    imageFile.value = file
    const reader = new FileReader()
    reader.onload = () => {
      imagePreview.value = reader.result as string
    }
    reader.readAsDataURL(file)
  }
}

const handleSaveBarang = async () => {
  const form = localForm.value

  if (!form.nama_barang || !form.id_kategori || !form.id_jenis || !form.harga || !form.link_barang) {
    Swal.fire('Warning', 'Lengkapi semua form!', 'warning')
    return
  }

  try {
    const formData = new FormData()
    formData.append('nama_barang', form.nama_barang)
    formData.append('id_kategori', form.id_kategori)
    formData.append('id_jenis', form.id_jenis)
    formData.append('sku', form.sku)
    formData.append('harga', form.harga.toString())
    formData.append('link_barang', form.link_barang)
    if (imageFile.value) formData.append('foto', imageFile.value)

    if (props.isEditing) {
      await axios.post(`/api/barangs/update`, formData)
      Swal.fire('Success', 'Barang berhasil diperbarui!', 'success')
    } else {
      await axios.post(`/api/barangs`, formData)
      Swal.fire('Success', 'Barang berhasil ditambahkan!', 'success')
    }

    emit('save', form)
    emit('close')
  } catch (error) {
    console.error(error)
    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan', 'error')
  }
}
</script>
