window.addEventListener("load", function() {
    const footer = document.createElement("footer")
    footer.classList.add("text-center", "py-4", "bg-dark", "text-white")
    footer.style.bottom = 0
    const footerText = document.createElement("p")
    footerText.innerText = "Проспект Авиаконструкторов, 28"
    footer.appendChild(footerText)
    document.body.appendChild(footer)
})