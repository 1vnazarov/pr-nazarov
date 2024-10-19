const form = document.getElementById("registerForm")
const emailInput = document.getElementById("email")
const msg = document.getElementById("notUniqueEmailMessage")
const incorrectEmailMsg = document.getElementById("emailInvalidFeedback")
const submitButton = document.getElementById("submit")

const uniqueEmail = async (event) => {
    const formData = new FormData()
    formData.append("email", event.target.value)

    try {
        const response = await fetch('testUsernameForExists.php', {
            method: 'POST',
            body: formData
        })
        form.classList.toggle("was-validated", !response.ok)
        msg.classList.toggle("d-none", !response.ok)
        incorrectEmailMsg.classList.toggle("d-none", response.ok)
        emailInput.classList.toggle("is-invalid", response.ok)
        submitButton.disabled = response.ok
    } catch (error) {
        console.error('Ошибка:', error)
    }
}

emailInput.addEventListener("blur", uniqueEmail)