<template>
    <svg
      class="loading-icon"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 50 50"
      :width="size"
      :height="size"
    >
      <circle
        class="path"
        cx="25"
        cy="25"
        r="20"
        fill="none"
        stroke-width="5"
      />
    </svg>
  </template>
  
  <script>
  export default {
    name: "LoadingIcon",
    props: {
      size: {
        type: Number,
        default: 50, // Default size is 50x50
      },
    },
  };
  </script>
  
  <style scoped>
  .loading-icon {
    animation: rotate 2s linear infinite;
  }
  
  .path {
    stroke: #a855f7; /* Change this to any desired color */
    stroke-linecap: round;
    animation: dash 1.5s ease-in-out infinite;
  }
  
  @keyframes rotate {
    100% {
      transform: rotate(360deg);
    }
  }
  
  @keyframes dash {
    0% {
      stroke-dasharray: 1, 150;
      stroke-dashoffset: 0;
    }
    50% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -35;
    }
    100% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -124;
    }
  }
  </style>
  