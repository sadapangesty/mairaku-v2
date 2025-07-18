<template>
  <div
    v-if="showModal"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
  >
    <div
      class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col"
    >
      <!-- Header -->
      <div
        class="sticky top-0 z-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between"
      >
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
          Pilih Barang
        </h3>
      </div>

      <!-- Content -->
      <div class="p-4 overflow-y-auto">
        <div class="flex flex-wrap gap-4 items-center">
          <img
            :src="`/assets/images/${product.foto}`"
            alt="Foto Produk"
            class="w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
          />
          <div class="flex flex-col justify-between">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
              {{ product.nama_barang }}
            </h4>
            <p class="text-sm text-gray-500">
              {{ product.kategori?.nama || "-" }} -
              {{ product.jenis?.nama || "-" }}
            </p>
            <p class="text-sm font-bold text-blue-600 dark:text-blue-400">
              Rp. {{ Number(product.harga).toLocaleString("id-ID") }}
            </p>
          </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
          <div class="mt-2">
            <label for="">Variant</label>
            <select v-model="form.variant" class="form-input mt-2">
              <option value="">Pilih Variant</option>
              <option
                v-for="variant in variants"
                :key="variant.id"
                :value="variant.id"
              >
                {{ variant.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, reactive, defineProps, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

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

export interface Variant {
  id: number;
  name: string;
  link: string;
  image: string;
}

const { showModal, product } = defineProps<{
  showModal: boolean;
  product: Product;
}>();

onMounted(() => {
  fetchVariants(product.id);
});

const variants = ref<Variant[]>([]);

const fetchVariants = async (productId: number) => {
  try {
    const response = await axios.get(`/api/products/${productId}/variants`);
    variants.value = response.data;
  } catch (error) {
    console.error("Error fetching variants:", error);
    return [];
  }
};

const form = reactive({
  productId: product.id,
  variant: {
    variantId: null,
    variantName: "",
    variantLink: "",
    variantImage: "",
  },
  productName: product.nama_barang,
  productPrice: product.harga,
  productSku: product.sku,
  productCategory: product.kategori,
  productType: product.jenis,
  productImage: product.foto,
  productLink: product.link_barang || "",
  notes: "",
});
</script>
