const email = document.getElementById("email")

const uniqueEmail = async (event) => {
    const formData = new FormData()
    formData.append("email", event.target.value)
    try {
        const response = await fetch('testUsernameForExists.php', {
            method: 'POST',
            body: formData
        })
        console.log(response)
        const msg = document.getElementById("notUniqueEmailMessage")
        if (response.ok) {
            msg.classList.remove("d-none")
            msg.classList.add("d-block")
        }
        else {
            msg.classList.add("d-none")
            msg.classList.remove("d-block")
        }
        document.getElementById("submit").disabled = !response.ok
    } catch (error) {
        console.log('Ошибка:', error)
    }
}
email.addEventListener("blur", uniqueEmail)