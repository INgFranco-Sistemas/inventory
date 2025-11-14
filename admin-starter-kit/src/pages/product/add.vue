<script setup>
import { VCheckbox, VCol } from 'vuetify/components';

import {
  useDropZone,
  useFileDialog,
  useObjectUrl,
} from '@vueuse/core'
import { $api } from '@/utils/api';
import { onMounted } from 'vue';

const dropZoneRef = ref()
const fileData = ref([])
const { open, onChange } = useFileDialog({ accept: 'image/*', multiple: false })
function onDrop(DroppedFiles) {
  DroppedFiles?.forEach(file => {
    if (file.type.slice(0, 6) !== 'image/') {
      alert('Only image files are allowed')
      
      return
    }
    fileData.value.push({
      file,
      url: useObjectUrl(file).value ?? '',
    })
  })
}

onChange(selectedFiles => {
  if(fileData.value.length == 1) {
    alert('Solo permite una imagen')
    return
  }
  if (!selectedFiles)
    return
  for (const file of selectedFiles) {
    fileData.value.push({
      file,
      url: useObjectUrl(file).value ?? '',
    })
  }
})
useDropZone(dropZoneRef, onDrop)
const categories = ref([]);
const sucursales = ref([]);
const units = ref([]);
const warehouses = ref([]);

const product = ref({
  title: '',
  sku: '',
  price_general: 0,
  price_company: 0,
  description: '',
  product_categorie_id: '',
  is_gift: 1,
  is_discount: 1,
  max_discount: 0,
  tax_selected: 1,
  importe_iva: 0,
  disponibilidad: 1,
  status: 1,  
});

const warehouse_stock_id = ref(null);
const unit_stock_id = ref(null);
const stock = ref(0);

const sucursale_price_id = ref(null);
const unit_price_id = ref(null);
const type_client_price = ref(null);
const price = ref(0);

const config = async() => {
  try {
    const resp = await $api("products/config",{
      method: 'GET',
      onResponseError({response}){
        console.log(response._data.error);
      }
    });
    console.log(resp);
    categories.value = resp.categories;
    sucursales.value = resp.sucursales;
    units.value = resp.units;
    warehouses.value = resp.warehouses;
  } catch (error) {
    console.log(error);
  }
}

onMounted(() => {
  config();
})

definePage({meta: {permission: 'register_product', }});
</script>
<template>
  <div>
    <div class="d-flex flex-wrap justify-space-between gap-4 mb-6">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 mb-1">
          💻 Add New product
        </h4>
        <p class="text-body-1 mb-0">
          Orders placed across your store
        </p>
      </div>      
    </div>

    <VRow>
      <VCol md="8">
        <VCard
          class="mb-6"
          title="Información del Producto"
        >
          <VCardText>
            <VRow>
              <VCol cols="12">
                <VTextField
                  label="Nombre del Producto"
                  v-model="product.title"
                  placeholder="iPhone 14"
                />
              </VCol>
              <VCol
                cols="12"
                md="4"
              >
                <VTextField
                  label="Sku:"
                  v-model="product.sku"
                  placeholder="FXSK123U"
                />
              </VCol>
              <VCol
                cols="12"
                md="4"
              >
                <VTextField
                  label="Precio (Cliente Final):"
                  v-model="product.price_general"
                  placeholder="S/. 50.00"
                />
              </VCol>
              <VCol
                cols="12"
                md="4"
              >
                <VTextField
                  label="Precio (Cliente Empresa):"
                  v-model="product.price_company"
                  placeholder="S/. 100.00"
                />
              </VCol>
              <VCol>
                <VLabel class="mb-1">
                  Descripción
                </VLabel>
                <TiptapEditor
                  v-model="product.description"
                  class="border rounded-lg"
                />
              </VCol>
            </VRow>
          </VCardText>
        </VCard>

        <!-- 👉 Product Image -->
        <VCard class="mb-6">
          <VCardItem>
            <template #title>
              Product Image
            </template>
            <template #append>
              <h6 class="text-h6 text-primary cursor-pointer">
                Add Media from URL
              </h6>
            </template>
          </VCardItem>

          <VCardText>
            <div class="flex">
              <div class="w-full h-auto relative">
                <div
                  ref="dropZoneRef"
                  class="cursor-pointer"
                  @click="() => open()"
                >
                  <div
                    v-if="fileData.length === 0"
                    class="d-flex flex-column justify-center align-center gap-y-2 pa-12 border-dashed drop-zone"
                  >
                    <VAvatar
                      variant="tonal"
                      color="secondary"
                      rounded
                    >
                      <VIcon icon="ri-upload-2-line" />
                    </VAvatar>
                    <h4 class="text-h4 text-wrap">
                      Drag and Drop Your Image Here.
                    </h4>
                    <span class="text-disabled">or</span>

                    <VBtn
                      variant="outlined"
                      size="small"
                    >
                      Browse Images
                    </VBtn>
                  </div>

                  <div
                    v-else
                    class="d-flex justify-center align-center gap-3 pa-8 border-dashed drop-zone flex-wrap"
                  >
                    <VRow class="match-height w-100">
                      <template
                        v-for="(item, index) in fileData"
                        :key="index"
                      >
                        <VCol
                          cols="12"
                          sm="4"
                        >
                          <VCard :ripple="false">
                            <VCardText
                              class="d-flex flex-column"
                              @click.stop
                            >
                              <VImg
                                :src="item.url"
                                width="200px"
                                height="150px"
                                class="w-100 mx-auto"
                              />
                              <div class="mt-2">
                                <span class="clamp-text text-wrap">
                                  {{ item.file.name }}
                                </span>
                                <span>
                                  {{ item.file.size / 1000 }} KB
                                </span>
                              </div>
                            </VCardText>
                            <VCardActions>
                              <VBtn
                                variant="text"
                                block
                                @click.stop="fileData.splice(index, 1)"
                              >
                                Remove File
                              </VBtn>
                            </VCardActions>
                          </VCard>
                        </VCol>
                      </template>
                    </VRow>
                  </div>
                </div>
              </div>
            </div>
          </VCardText>
        </VCard>

        <!-- 👉 Variants -->
        <VCard
          title="Existencias"
          class="mb-6"
        >
          <VCardText>
            <VRow>
              <VCol
                cols="4"
                md="4"
              >
                <VSelect
                  :items="warehouses"
                  item-title="name"
                  item-value="id"
                  placeholder="Select"
                  v-model="warehouse_stock_id"
                  label="Almacenes"
                />
              </VCol>

              <VCol
                cols="4"
                md="4"
              >
                <VSelect
                  :items="units"
                  item-title="name"
                  item-value="id"
                  placeholder="Select"
                  v-model="unit_stock_id"
                  label="Unidades"
                />
              </VCol>

              <VCol
                cols="3"
                md="3"
              >
                <VTextField
                  label="Stock"
                  type="number"
                  v-model="stock"
                  placeholder=""
                />
              </VCol>

              <VCol
                cols="1"
                md="1"
              >
                <VBtn
                  prepend-icon="ri-add-line"
                >
                </VBtn>            
              </VCol>
            </VRow>              
          </VCardText>
          
          <VCardText>
            <VTable>
              <thead>
                <tr>
                  <th class="text-uppercase">
                    Almacen
                  </th>
                  <th class="text-uppercase">
                    Unidad
                  </th>
                  <th class="text-uppercase">
                    Stock
                  </th>
                  <th class="text-uppercase">
                    Acción
                  </th>
                </tr>
              </thead>
  
              <tbody>
                
              </tbody>
            </VTable>
          </VCardText>
        </VCard>

        <VCard
          title="Multiples Precios"
          class="mb-6"
        >
          <VCardText>
            <VRow>
              <VCol
                cols="4"
                md="3"
              >
                <VSelect
                  :items="sucursales"
                  item-title="name"
                  item-value="id"
                  placeholder="Select"
                  v-model="sucursale_price_id"
                  label="Sucursales"
                />
              </VCol>

              <VCol
                cols="4"
                md="3"
              >
                <VSelect
                  :items="units"
                  item-title="name"
                  item-value="id"
                  placeholder="Select"
                  label="Unidades"
                  v-model="unit_price_id"
                />
              </VCol>

              <VCol
                cols="4"
                md="3"
              >
                <VSelect
                  :items="[
                    {
                      id: 1,
                      name: 'Cliente Final'
                    },
                    {
                      id: 2,
                      name: 'Cliente Empresa'
                    }
                  ]"
                  item-title="name"
                  item-value="id"
                  placeholder="Select"
                  label="Tipo de Cliente"
                  v-model="type_client_price"
                />
              </VCol>

              <VCol
                cols="3"
                md="2"
              >
                <VTextField
                  label="Price"
                  type="number"
                  placeholder=""
                  v-model="price"
                />
              </VCol>

              <VCol
                cols="1"
                md="1"
              >
                 <VBtn
                    prepend-icon="ri-add-line"
                  >
                  </VBtn>           
              </VCol>
            </VRow>              
          </VCardText>
          
          <VCardText>
            <VTable>
              <thead>
                <tr>
                  <th class="text-uppercase">
                    Sucursal
                  </th>
                  <th class="text-uppercase">
                    Unidad
                  </th>
                  <th class="text-uppercase">
                    Tipo de Cliente
                  </th>
                  <th class="text-uppercase">
                    Stock
                  </th>
                  <th class="text-uppercase">
                    Acción
                  </th>
                </tr>
              </thead>
  
              <tbody>
                
              </tbody>
            </VTable>
          </VCardText>
        </VCard>
      </VCol>

      <VCol 
        md="4"
        cols="12"
      >
        <!-- 👉 Organize -->
        <VCard title="Adicionales">
          <VCardText>
            <div class="d-flex flex-column gap-y-5">              
              <VSelect
                placeholder="Select Category"
                label="Categoria"
                :items="categories"
                item-title="name"
                item-value="id"
                v-model="product.product_categorie_id"
              >
              </VSelect>              
            </div>

            <div class="my-3">
              <p class="my-0">¿Regalo?</p>                
              <VCheckbox 
                label="SI"
                value="2"
                v-model="product.is_gift"
              />
            </div>
            
            <div class="d-flex">
              <div>
                <p class="my-0">¿Tiene Descuento?</p>                
                <VCheckbox 
                  label="SI"
                  value="2"
                  v-model="product.is_discount"
                />
              </div>              
              
              <div class="ml-5" v-if="product.is_discount == 2">
                <VTextField
                  label="% de descuento máximo:"
                  type="number"
                  v-model="product.max_discount"
                  placeholder="18%"
                />
              </div>
            </div>
          </VCardText>
        </VCard>

        <VCard title="Especificaciones" class="my-5">
          <VCardText>
            <div class="d-flex flex-column gap-y-5">
              <VSelect
                placeholder="Select"
                label="Tipo de Impuesto"
                :items="[
                  {
                    id: 1,
                    title: 'Sujeto a Impuesto'
                  },
                  {
                    id: 2,
                    title: 'Libre de Impuesto'
                  }
                ]"
                item-title="title"
                item-value="id"
                v-model="product.tax_selected"
              >
              </VSelect>
              
              <VTextField
                label="Importe IVA:"
                type="number"
                v-model="product.importe_iva"
                placeholder="18%"
              />
              
              <VSelect
                placeholder="Select"
                label="Disponibilidad"
                :items="[
                  {
                    id: 1,
                    title: 'Vender sin Stock'
                  },
                  {
                    id: 2,
                    title: 'No Vender sin Stock'
                  }
                ]"
                item-title="title"
                item-value="id"
                v-model="product.disponibilidad"
              >
              </VSelect>
    
              <VSelect
                placeholder="Select"
                label="Estado"
                :items="[
                  {
                    id: 1,
                    name: 'Activo'
                  },
                  {
                    id: 2,
                    name: 'Inactivo'
                  }
                ]"
                item-title="name"
                item-value="id"
                v-model="product.state"
              />              
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
  .drop-zone {
    border: 1px dashed rgba(var(--v-theme-on-surface), 0.12);
    border-radius: 8px;
  }
</style>