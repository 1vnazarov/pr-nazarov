(function () {
    const alerts = document.getElementsByClassName("alert")
    const h1 = document.getElementsByTagName("h1")[0]
    h1.classList.toggle("mb-4", alerts === 0)
    h1.classList.toggle("mb-2", alerts > 0)
  })()