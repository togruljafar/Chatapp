// main page
const createPostBtn = document.querySelector('.create-post-btn'),
	  closeBtn = document.querySelector('.close-btn'),
	  profDesc = document.querySelector('.inpost-desc'),
	  postForm = document.querySelector('.create-post-form'),
	  message  = document.querySelector('.alert');

if (message) {
	createPostBtn.classList.add("d-none");
	closeBtn.classList.remove("d-none");
	profDesc.classList.remove("d-none");
	postForm.classList.remove("d-none");
}

createPostBtn.addEventListener('click', () => {
	createPostBtn.classList.add("d-none");
	closeBtn.classList.remove("d-none");
	profDesc.classList.remove("d-none");
	postForm.classList.remove("d-none");
})

closeBtn.addEventListener('click', () => {
	closeBtn.classList.add("d-none");
	profDesc.classList.add("d-none");
	postForm.classList.add("d-none");
	createPostBtn.classList.remove("d-none");
})