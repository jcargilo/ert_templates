<template>
  <div class="mb-0 flex w-full">
    <div class="relative w-full rounded-lg border border-gray-200 bg-white p-3 hover:border-gray-300 hover:shadow-md">
      <div class="absolute top-0 left-0 z-0 h-[62px] w-full rounded-t-lg bg-gradient-to-r from-gray-200 via-gray-300 to-secondary"></div>

      <div class="relative z-10 flex h-full flex-col space-y-4">
        <img class="mx-auto h-32 w-32 rounded-full object-cover" :src="member.PhotoURL" alt="" />

        <div class="flex-1">
          <h3 class="text-lg font-medium leading-6">{{ member.Name }}</h3>
          <p class="text-[14px] text-gray-500">{{ member.ShortBio }}</p>
        </div>

        <div>
          <button class="w-full" aria-label="Bio" @click="$emit('openBio', member)">
            <span class="font-medium">Bio</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    member: Object,
  },
}
</script>
