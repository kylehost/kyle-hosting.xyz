function sendEmail(event) {
  event.preventDefault();
  const form = document.getElementById("contact-form");
  const formData = new FormData(form);
  emailjs.sendForm("LcP9Vbb3Pvk4AOf3F", "template_tryu37b", formData).then(
    (result) => {
      alert("Message sent successfully!");
      form.reset();},
    (error) => {
      alert("Error sending message: " + error.text);});}