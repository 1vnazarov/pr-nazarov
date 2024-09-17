window.onload = () => {
    const footer = document.createElement("footer")
    const footerText = document.createElement("p")
    footerText.innerText = "Проспект Авиаконструкторов, 28"
    footer.appendChild(footerText)
    document.body.appendChild(footer)
}