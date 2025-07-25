<template>
  <div class="mb-6">
    <label :for="id" class="block mb-2 text-gray-600 font-medium text-sm">
      {{ label }}
    </label>
    <input
      :id="id"
      :type="type"
      :placeholder="placeholder"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg text-base transition-colors duration-200 bg-white placeholder-gray-400 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-100"
    />
  </div>
</template>

<script setup lang="ts">
interface Props {
  id: string
  label: string
  type?: string
  placeholder?: string
  modelValue: string
}

defineProps<Props>()
defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>