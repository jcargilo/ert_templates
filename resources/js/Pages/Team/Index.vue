<template>
  <section id="row-1" class="pt-10 pb-10">
    <div class="max-w-screen-xl space-y-12 px-4 text-center lg:mx-auto xl:px-0">
      <div class="space-y-5 sm:mx-auto sm:max-w-xl sm:space-y-4 lg:max-w-5xl">
        <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ name }}</h2>
        <p class="text-xl text-gray-500">
          Ornare sagittis, suspendisse in hendrerit quis. Sed dui aliquet lectus sit pretium egestas vel mattis neque.
        </p>
      </div>

      <div class="mx-auto max-w-5xl" v-if="team.length > 0">
        <ul role="list" class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5">
          <template v-for="(member, index) in team">
            <Member :member="member" :key="index" @openBio="openBio" />
          </template>
        </ul>
      </div>

      <p class="rounded-lg bg-white p-12 text-xl text-gray-500 shadow-lg sm:mx-auto sm:max-w-xl" v-else>
        No members have been added to the {{ name }}.
      </p>
    </div>

    <div
      class="fixed inset-0 z-10 overflow-y-auto"
      :class="bioOpen ? '' : 'invisible'"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          :class="{
            'opacity-100': bioOpen,
            'opacity-0': !bioOpen,
          }"
          aria-hidden="true"
          @click="closeBio"
        ></div>

        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

        <div
          class="inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:p-6 sm:align-middle lg:max-w-4xl"
          :class="{
            'translate-y-0 opacity-100 sm:scale-100': bioOpen,
            'translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95': !bioOpen,
          }"
        >
          <div class="absolute top-0 right-0 pt-4 pr-4">
            <button
              type="button"
              class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              @click="closeBio"
            >
              <span class="sr-only">Close</span>
              <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="sm:flex sm:items-start">
            <div class="mt-3 sm:mt-0 sm:ml-4">
              <h3 class="text-lg font-medium leading-6 text-blue-500" id="modal-title">{{ member.Name }}</h3>
              <p class="leading-6 text-gray-700">{{ member.ShortBio }}</p>
              <div class="mt-3 border-t border-gray-400 pt-3">
                <p class="leading-relaxed text-gray-500" v-html="member.LongBio"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Member from './Member'

export default {
  components: {
    Member,
  },

  props: {
    name: String,
    team: Array,
  },

  data() {
    return {
      member: {},
      bioOpen: false,
    }
  },

  methods: {
    closeBio: function () {
      this.member = {}
      this.bioOpen = false
    },

    openBio: function (member) {
      this.member = member
      this.bioOpen = true
    },
  },
}
</script>
