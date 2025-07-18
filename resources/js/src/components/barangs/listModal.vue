<template>
  <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col">
      <!-- Header + Search (fixed di atas modal) -->
      <div class="sticky top-0 z-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 p-4 flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Katalog Barang</h3>
          <button
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg font-medium"
            @click="showModalAdd = true"
          >
            + Tambah Barang
          </button>
        </div>

        <input
          type="text"
          v-model="searchQuery"
          @input="onSearchInput"
          placeholder="Cari produk..."
          class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white text-sm"
        />
      </div>

      <!-- Konten utama yang scrollable -->
      <div class="px-6 py-4 flex-grow overflow-auto flex flex-col gap-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">Daftar barang yang tersedia.</p>

        <!-- Product Grid -->
        <div
          v-if="products.length > 0"
          class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 flex-grow"
        >
          <div
            v-for="product in products"
            :key="product.id"
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-md transition hover:shadow-lg flex flex-col"
          >
            <div class="h-28 w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
              <img
                :src="`/assets/images/${product.foto}` || '/placeholder.svg'"
                class="object-cover w-full h-full"
                alt="Foto Produk"
              />
            </div>
            <div class="p-3 flex flex-col flex-grow">
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ product.nama_barang }}</h4>
              <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ product.jenis?.nama || '-' }}</p>
              <div class="mt-auto text-sm font-bold text-blue-600 dark:text-blue-400">Rp. {{ formatPrice(product.harga) }}</div>
            </div>
            <div class="px-3 pb-3">
              <button
                class="mt-1 w-full bg-blue-600 hover:bg-blue-700 text-white text-xs py-1.5 rounded-lg flex items-center justify-center gap-1"
                @click="onSelectProduct(product)"
              >
                <Plus class="w-3 h-3" /> Pilih & Konfigurasi
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <p v-else class="text-center text-gray-500 dark:text-gray-400">Produk tidak ditemukan.</p>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex justify-center gap-2 mt-4">
          <button
            class="px-3 py-1 rounded-lg text-sm border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
          >
            Prev
          </button>
          <button
            v-for="page in pagesToShow"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-3 py-1 rounded-lg text-sm',
              page === currentPage
                ? 'bg-blue-600 text-white'
                : 'border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            {{ page }}
          </button>
          <button
            class="px-3 py-1 rounded-lg text-sm border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            :disabled="currentPage === totalPages"
            @click="goToPage(currentPage + 1)"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Footer (fixed di bawah modal) -->
      <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 bg-white dark:bg-gray-900 z-20">
        <button
          class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg"
          @click="$emit('close')"
        >
          Tutup
        </button>
      </div>
    </div>

    <!-- Modal Tambah Barang -->
    <FormModalBarang
      v-if="showModalAdd"
      :showModal="showModalAdd"
      @close="showModalAdd = false"
      @save="fetchProducts(currentPage, searchQuery)"
    />
    <FormSelectProduct
    v-if="showModalSelectProduct"
    :showModal="showModalSelectProduct"
    :product="selectProduct"
    @close="showModalSelectProduct = false"
    ></FormSelectProduct>
  </div>
</template>



<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import FormModalBarang from '@/components/barangs/FormModal2.vue';
import FormSelectProduct from '@/components/quatitions/addItemModal.vue';

defineProps({ showModal: Boolean });
const emit = defineEmits(["close"]);

export interface Kategori {
  id_kategori: number;
  nama: string;
  created_at: string;
  updated_at: string;
}

export interface Jenis {
  id_jenis: number;
  nama: string;
}

export interface Product {
  id: number;
  nama_barang: string;
  id_kategori: number;
  id_jenis: number;
  sku: string;
  harga: string;
  link_barang: string | null;
  foto: string;
  created_at: string;
  updated_at: string;
  kategori: Kategori;
  jenis: Jenis;
}

const showModalAdd = ref(false);
const showModalSelectProduct = ref(false);
const products = ref<Product[]>([]);
const selectProduct = ref<Product | null>(null);
const searchQuery = ref("");
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = 10;

async function fetchProducts(page = 1, search = "") {
  try {
    const response = await axios.get("/api/barangs", {
      params: { page, search, per_page: perPage },
    });
    const data = response.data;
    products.value = data.data.data;
    currentPage.value = data.current_page;
    totalPages.value = data.last_page;
  } catch (error) {
    console.error("Error fetching products:", error);
    products.value = [];
    currentPage.value = 1;
    totalPages.value = 1;
  }
}

const pagesToShow = computed(() => {
  const pages = [];
  const maxPages = 5;
  let start = Math.max(1, currentPage.value - Math.floor(maxPages / 2));
  let end = start + maxPages - 1;
  if (end > totalPages.value) {
    end = totalPages.value;
    start = Math.max(1, end - maxPages + 1);
  }
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

function goToPage(page: number) {
  if (page < 1 || page > totalPages.value) return;
  currentPage.value = page;
  fetchProducts(page, searchQuery.value);
}

let searchTimeout: any = null;
function onSearchInput() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    currentPage.value = 1;
    fetchProducts(1, searchQuery.value);
  }, 500);
}

function onSelectProduct(product: Product) {
  showModalSelectProduct.value = true;
  selectProduct.value = product;
}

function formatPrice(price: string): string {
  const numberPrice = Number(price);
  return isNaN(numberPrice) ? price : numberPrice.toLocaleString("id-ID");
}

onMounted(() => {
  fetchProducts(currentPage.value, searchQuery.value);
});
</script>
