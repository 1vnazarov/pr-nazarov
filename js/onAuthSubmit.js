const onAuthSubmit = async (e) => {
    e.preventDefault()
    await fetch("https://pr-nazarov.сделай.site/authorize_user.php", {
        method: "POST",
        body: JSON.stringify({email: document.getElementById("email").value, password: document.getElementById("password").value})
    })
}