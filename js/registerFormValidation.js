window.onload = () => {
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const inputs = this.querySelectorAll('input, select')
        inputs.forEach((input) => {
            input.classList.add('submitted')
        })
        console.log(this.checkValidity())
        if (!this.checkValidity()) {
            event.preventDefault()
        }
    })
}