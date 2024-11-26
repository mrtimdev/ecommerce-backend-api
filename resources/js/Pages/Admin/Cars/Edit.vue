<template>
  <DefaultLayout>
    <Head :title="$t('car.edit')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div
              class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 mb-6"
            >
              <div>
                <Label
                  for_id="sourced_link"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("sourced_link") }}</Label
                >
                <input
                  type="text"
                  ref="sourced_link"
                  id="sourced_link"
                  v-model="form.sourced_link"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('sourced_link')"
                />
                <InputError :message="form.errors.sourced_link" class="mt-2" />
              </div>
              <div>
                <Label
                  for_id="listing_date"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("listing_date") }}</Label
                >
                <input
                  type="date"
                  ref="listing_date"
                  id="listing_date"
                  v-model="form.listing_date"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('listing_date')"
                />
                <InputError :message="form.errors.listing_date" class="mt-2" />
              </div>
              <div>
                <Label
                  :isRequired="true"
                  for_id="code"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("code") }}</Label
                >
                <input
                  autofocus
                  type="text"
                  ref="code"
                  id="code"
                  v-model="form.code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('code')"
                />
                <InputError :message="form.errors.code" class="mt-2" />
              </div>

              <div>
                <Label
                  :isRequired="true"
                  for_id="name"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("name") }}</Label
                >
                <input
                  type="text"
                  ref="name"
                  id="name"
                  v-model="form.name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('name')"
                />
                <InputError :message="form.errors.name" class="mt-2" />
              </div>
            </div>

            <div class="mb-6">
              <fieldset
                class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg"
              >
                <legend
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("basic_info") }}
                </legend>
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                >
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="year"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("year") }}</Label
                    >
                    <input
                      type="number"
                      ref="year"
                      id="year"
                      v-model="form.year"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('year')"
                    />
                    <InputError :message="form.errors.year" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="mileage"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("mileage") }}</Label
                    >
                    <input
                      autofocus
                      type="text"
                      ref="mileage"
                      id="mileage"
                      v-model="form.mileage"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('mileage')"
                    />
                    <InputError :message="form.errors.mileage" class="mt-2" />
                  </div>

                  <div>
                    <div>
                      <Label
                        for_id="size"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                        >{{ $t("vehicle_type") }}</Label
                      >
                      <input
                        autofocus
                        type="text"
                        ref="size"
                        id="size"
                        v-model="form.size"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                        :placeholder="$t('vehicle_type')"
                      />
                      <InputError :message="form.errors.size" class="mt-2" />
                    </div>
                  </div>
                  <div class="">
                    <Label
                      for_id="engine_volume"
                      :isRequired="true"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("engine_volume") }}</Label
                    >
                    <input
                      autofocus
                      type="number"
                      ref="engine_volume"
                      id="engine_volume"
                      v-model="form.engine_volume"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('engine_volume')"
                    />
                    <InputError :message="form.errors.engine_volume" class="mt-2" />
                  </div>

                  <div class="">
                    <Label
                      for_id="door"
                      :isRequired="true"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("door") }}</Label
                    >
                    <input
                      autofocus
                      type="number"
                      ref="door"
                      id="door"
                      v-model="form.door"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('door')"
                    />
                    <InputError :message="form.errors.door" class="mt-2" />
                  </div>

                  <div class="">
                    <Label
                      for_id="cylinder"
                      :isRequired="true"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("cylinder") }}</Label
                    >
                    <input
                      autofocus
                      type="number"
                      ref="cylinder"
                      id="cylinder"
                      v-model="form.cylinder"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('cylinder')"
                    />
                    <InputError :message="form.errors.cylinder" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="water_flood_damaged"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("water_flood_damaged") }}</Label
                    >
                    <select
                      v-model="form.water_flood_damaged"
                      id="water_flood_damaged"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.water_flood_damaged" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="former_rental_car"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("former_rental_car") }}</Label
                    >
                    <select
                      v-model="form.former_rental_car"
                      id="former_rental_car"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.former_rental_car" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="former_taxi"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("former_taxi") }}</Label
                    >
                    <select
                      v-model="form.former_taxi"
                      id="former_taxi"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.former_taxi" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="recovered_theft"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("recovered_theft") }}</Label
                    >
                    <select
                      v-model="form.recovered_theft"
                      id="recovered_theft"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.recovered_theft" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="police_car"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("police_car") }}</Label
                    >
                    <select
                      v-model="form.police_car"
                      id="police_car"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.police_car" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="salvage_record"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("salvage_record") }}</Label
                    >
                    <select
                      v-model="form.salvage_record"
                      id="salvage_record"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.salvage_record" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="fuel_conversion"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("fuel_conversion") }}</Label
                    >
                    <select
                      v-model="form.fuel_conversion"
                      id="fuel_conversion"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.fuel_conversion" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="modified_seats"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("modified_seats") }}</Label
                    >
                    <select
                      v-model="form.modified_seats"
                      id="modified_seats"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.modified_seats" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="is_featured"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("is_featured") }}</Label
                    >
                    <select
                      v-model="form.is_featured"
                      id="is_featured"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="false">{{ $t("no") }}</option>
                      <option :value="true">{{ $t("yes") }}</option>
                    </select>
                    <InputError :message="form.errors.is_featured" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="is_active"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ $t("is_active") }}</Label
                    >
                    <select
                      v-model="form.is_active"
                      id="is_active"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    >
                      <option :value="true">{{ $t("active") }}</option>
                      <option :value="false">{{ $t("inactive") }}</option>
                    </select>
                    <InputError :message="form.errors.is_active" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="mb-6">
              <fieldset
                class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg"
              >
                <legend
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("specifications") }}
                </legend>
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                >
                  <!-- Category Selection -->
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="category"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("category") }}</Label
                    >
                    <MultiSelect
                      id="category"
                      v-model="form.category"
                      :options="categories"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.category" class="mt-2" />
                  </div>

                  <!-- Brand Selection -->
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="brand"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("brand") }}</Label
                    >

                    <MultiSelect
                      id="brand"
                      v-model="form.brand"
                      :options="brands"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                      @select="handleBrandChange"
                      @remove="handleBrandRemove"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.brand" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="model"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("model") }}</Label
                    >

                    <MultiSelect
                      id="model"
                      v-model="form.model"
                      :options="models"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.model" class="mt-2" />
                  </div>

                  <!-- Condition Selection -->
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="condition"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("condition") }}</Label
                    >

                    <MultiSelect
                      id="condition"
                      v-model="form.condition"
                      :options="conditions"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.condition" class="mt-2" />
                  </div>

                  <!-- Fuel Type Selection -->
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="fuel_type"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("fuel_type") }}
                    </Label>
                    <MultiSelect
                      id="fuel_type"
                      v-model="form.fuel_type"
                      :options="fuel_types"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.fuel_type" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="transmission_type"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("transmission_type") }}
                    </Label>
                    <MultiSelect
                      id="transmission_type"
                      v-model="form.transmission_type"
                      :options="transmission_types"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.transmission_type" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="steering"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("steering") }}</Label
                    >
                    <MultiSelect
                      id="steering"
                      v-model="form.steering"
                      :options="steerings"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.steering" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="color"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("color") }}
                    </Label>
                    <MultiSelect
                      id="color"
                      v-model="form.color"
                      :options="colors"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.color" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="drive_type"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("drive_type") }}
                    </Label>
                    <MultiSelect
                      id="drive_type"
                      v-model="form.drive_type"
                      :options="drive_types"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.drive_type" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="passengers"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("passengers") }}
                    </Label>
                    <MultiSelect
                      id="passenger"
                      v-model="form.passenger"
                      :options="passengers"
                      :multiple="false"
                      track-by="id"
                      :custom-label="
                        (option) => `${t('seat.count', { count: parseInt(option.name) })}`
                      "
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.passenger" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="location"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("location") }}
                    </Label>
                    <MultiSelect
                      id="location"
                      v-model="form.location"
                      :options="locations"
                      :multiple="false"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.location" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="status"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("status") }}
                    </Label>
                    <MultiSelect
                      id="status"
                      v-model="form.status"
                      :options="statuses"
                      :multiple="false"
                      :custom-label="(option) => `${$t(option)}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.status" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="mb-6">
              <fieldset
                class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg"
              >
                <legend
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("other_options") }}
                </legend>
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2"
                >
                  <div>
                    <Label
                      for_id="hot_marks"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("hot_marks") }}
                    </Label>
                    <MultiSelect
                      id="hot_mark"
                      v-model="form.hot_marks"
                      :options="hot_marks"
                      :multiple="true"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.hot_marks" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="options"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("options") }}
                    </Label>
                    <MultiSelect
                      id="option"
                      v-model="form.options"
                      :options="options"
                      :multiple="true"
                      track-by="id"
                      :custom-label="(option) => `${option.name}`"
                    >
                    </MultiSelect>
                    <InputError :message="form.errors.options" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="grid gap-6 grid-cols-1 mb-6">
              <div>
                <Label
                  :isRequired="true"
                  for_id="total_price"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("total_price") }}</Label
                >
                <input
                  type="text"
                  ref="total_price"
                  id="total_price"
                  readonly="readonly"
                  v-model="form.total_price"
                  class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('total_price')"
                />
                <InputError :message="form.errors.total_price" class="mt-2" />
              </div>
            </div>

            <div class="mb-6">
              <fieldset
                class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg"
              >
                <legend
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("payment_details") }}
                </legend>
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-6"
                >
                  <div>
                    <Label
                      for_id="car_price"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("car_price") }}</Label
                    >
                    <input
                      type="text"
                      ref="car_price"
                      id="car_price"
                      v-model="form.car_price"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('car_price')"
                    />
                    <InputError :message="form.errors.car_price" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="towing_export_document"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("export_license") }}</Label
                    >
                    <input
                      type="text"
                      ref="towing_export_document"
                      id="towing_export_document"
                      v-model="form.towing_export_document"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('export_license')"
                    />
                    <InputError
                      :message="form.errors.towing_export_document"
                      class="mt-2"
                    />
                  </div>
                  <div>
                    <Label
                      for_id="shipping"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("shipping") }}</Label
                    >
                    <input
                      type="text"
                      ref="shipping"
                      id="shipping"
                      v-model="form.shipping"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('shipping')"
                    />
                    <InputError :message="form.errors.shipping" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="tax_import"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("tax_import") }}</Label
                    >
                    <input
                      type="text"
                      ref="tax_import"
                      id="tax_import"
                      v-model="form.tax_import"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('tax_import')"
                    />
                    <InputError :message="form.errors.tax_import" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="clearance"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("clearance") }}</Label
                    >
                    <input
                      type="text"
                      ref="clearance"
                      id="clearance"
                      v-model="form.clearance"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('clearance')"
                    />
                    <InputError :message="form.errors.clearance" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      for_id="service"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("service") }}</Label
                    >
                    <input
                      type="text"
                      ref="service"
                      id="service"
                      v-model="form.service"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('service')"
                    />
                    <InputError :message="form.errors.service" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="mb-6">
              <fieldset
                class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg"
              >
                <legend
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("payment_terms") }}
                </legend>
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
                >
                  <div>
                    <Label
                      for_id="first_payment"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("first_payment") }}</Label
                    >
                    <input
                      type="text"
                      ref="first_payment"
                      id="first_payment"
                      readonly="readonly"
                      v-model="form.first_payment"
                      class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('first_payment')"
                    />
                    <InputError :message="form.errors.first_payment" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="second_payment"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("second_payment") }}</Label
                    >
                    <input
                      type="text"
                      ref="second_payment"
                      id="second_payment"
                      readonly="readonly"
                      v-model="form.second_payment"
                      class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('second_payment')"
                    />
                    <InputError :message="form.errors.second_payment" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      for_id="third_payment"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("third_payment") }}</Label
                    >
                    <input
                      type="text"
                      ref="third_payment"
                      id="third_payment"
                      readonly="readonly"
                      v-model="form.third_payment"
                      class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('third_payment')"
                    />
                    <InputError :message="form.errors.third_payment" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="mb-6">
              <Label
                for_id="youtube_link"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("youtube_link") }}</Label
              >
              <input
                type="text"
                ref="youtube_link"
                id="youtube_link"
                v-model="form.youtube_link"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                placeholder="https://www.youtube.com/watch?v=_FTDev"
              />
              <InputError :message="form.errors.youtube_link" class="mt-2" />
            </div>

            <div class="mb-6">
              <Label
                for_id="description"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("description") }}</Label
              >
              <!-- <SummernoteEditor v-model="form.description" /> -->
              <Textarea rows="8" id="description" v-model="form.description"></Textarea>
              <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div class="grid gap-6 md:grid-cols-2">
              <div>
                <Label
                  for_id="featured_image"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("featured_image") }}</Label
                >
                <FileUpload v-model="form.featured_image" target_input="featured_image" />
                <InputError :message="form.errors.featured_image" class="mt-2" />
              </div>
              <div>
                <Label
                  for_id="gallery_images"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("gallery_images") }}</Label
                >
                <FileUpload
                  :multiple="true"
                  target_input="gallery_images"
                  v-model="form.gallery_images"
                />
                <InputError :message="form.errors.gallery_images" class="mt-2" />
              </div>
            </div>

            <div class="button-group">
              <div class="flex justify-start gap-5 items-center">
                <Link
                  :href="route('cars.index')"
                  class="focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
                >
                  {{ $t("cancel") }}
                </Link>
                <input
                  type="submit"
                  :value="$t('update')"
                  class="focus:outline-none text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-1 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-900"
                />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import Textarea from "@/Components/Others/Textarea.vue";
import "summernote/dist/summernote-lite.css";
import "summernote/dist/summernote-lite.js";
import SummernoteEditor from "vue3-summernote-editor";
import Label from "@/Components/Others/Label.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import { Head, useForm, Link, usePage } from "@inertiajs/vue3";
import { reactive, ref, watch, onMounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import useHelper from "@/composables/useHelper";
const { generateSlug } = useHelper();

import { events } from "@/events";
import { useMainStore } from "@/stores/main";
const mainStore = useMainStore();

const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("cars"), url: "/admin/cars" },
  { label: t("create"), url: null, is_active: true },
]);

const models = ref([]);

const props = defineProps({
  car: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  brands: {
    type: Array,
    required: true,
  },
  // models: {
  //   type: Array,
  //   required: true,
  // },
  conditions: {
    type: Array,
    required: true,
  },
  fuel_types: {
    type: Array,
    required: true,
  },
  transmission_types: {
    type: Array,
    required: true,
  },
  colors: {
    type: Array,
    required: true,
  },
  drive_types: {
    type: Array,
    required: true,
  },
  passengers: {
    type: Array,
    required: true,
  },
  steerings: {
    type: Array,
    required: true,
  },
  hot_marks: {
    type: Array,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
  locations: {
    type: Array,
    required: true,
  },
});

const statuses = ["available", "booked", "sold"];

const form = useForm({
  sourced_link: props.car.sourced_link,
  listing_date: props.car.listing_date,
  code: props.car.code,
  name: props.car.name,
  slug: props.car.slug,
  plate_number: props.car.plate_number,
  total_price: props.car.total_price,
  car_price: props.car.car_price,
  year: props.car.year,
  mileage: props.car.mileage,
  description: props.car.description ?? "",
  featured_image: null,
  gallery_images: null,
  is_featured: props.car.is_featured,
  is_active: props.car.is_active,
  engine_volume: props.car.engine_volume,
  door: props.car.door,

  cylinder: props.car.cylinder,
  engine_power: props.car.engine_power,
  odometer_reading: props.car.odometer_reading,
  water_flood_damaged: props.car.water_flood_damaged,
  former_rental_car: props.car.former_rental_car,
  former_taxi: props.car.former_taxi,
  recovered_theft: props.car.recovered_theft,
  police_car: props.car.police_car,
  salvage_record: props.car.salvage_record,
  fuel_conversion: props.car.fuel_conversion,
  modified_seats: props.car.modified_seats,
  first_registered_date: props.car.first_registered_date,

  // Foreign keys
  category: props.car.category,
  condition: props.car.condition,
  brand: props.car.brand,
  model: props.car.model,
  fuel_type: props.car.fuel_type,
  transmission_type: props.car.transmission_type,
  color: props.car.color,
  steering: props.car.steering,
  drive_type: props.car.drive_type,
  passenger: props.car.passenger,
  location: props.car.location,

  hot_marks: props.car.hot_marks,
  options: props.car.options,
  size: props.car.size,

  status: props.car.status,

  youtube_link: props.car.youtube_link,
  towing_export_document: props.car.towing_export_document,
  shipping: props.car.shipping,
  tax_import: props.car.tax_import,
  clearance: props.car.clearance,
  service: props.car.service,
  first_payment: props.car.first_payment,
  second_payment: props.car.second_payment,
  third_payment: props.car.third_payment,
});

const total_price = ref(0);

watch(
  [
    () => form.car_price,
    () => form.towing_export_document,
    () => form.shipping,
    () => form.tax_import,
    () => form.clearance,
    () => form.service,
  ],
  ([car_price, towing_export_document, shipping, tax_import, clearance, service]) => {
    total_price.value =
      Number(car_price) +
      Number(towing_export_document) +
      Number(shipping) +
      Number(tax_import) +
      Number(clearance) +
      Number(service);
    form.total_price = total_price.value;
    form.first_payment =
      Number(car_price) + Number(towing_export_document) + Number(shipping);
    form.second_payment = Number(tax_import) + Number(clearance);
    form.third_payment = Number(service);
  }
);

const handleSubmit = () => {
  form.post(route("cars.update", props.car.id), {
    preserveScroll: true,
    onSuccess: (res) => {
      events.emit("toaster", {
        type: "success",
        action: "create",
        message: `${t("car")} [${form.name}] ${t("successfully_updated")}`,
      });
      form.reset("code", "name");
    },
    onError: () => {},
  });
};

const handleBrandChange = (brand) => {
  models.value = [];
  form.model = [];
  if (brand) {
    axios.get(route("models.by.brand", { ids: [brand.id] })).then((res) => {
      models.value = res?.data.models;
      for (const model of models.value) {
        if (model.id === props.car.model.id) {
          form.model = model;
        }
      }
    });
  }
};

const handleBrandRemove = (brand) => {
  models.value = [];
  form.model = [];
};

onMounted(() => {
  nextTick(() => {
    Array.from(document.querySelectorAll(".multiselect__tags")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
    Array.from(document.querySelectorAll(".multiselect__input")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });

    form.hot_marks = props.car.hot_marks;
    form.options = props.car.options;

    if (props.car.brand) {
      handleBrandChange(props.car.brand);
    }
  });
});
</script>
