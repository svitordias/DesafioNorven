// Script para consumir a API e exibir as vagas
document.addEventListener('DOMContentLoaded', function() {
    fetch('/wp-json/ntj/v1/jobs')
        .then(response => response.json())
        .then(data => {
            const jobList = document.querySelector('.ntj-job-list');
            data.forEach(job => {
                const listItem = document.createElement('li');
                listItem.className = 'ntj-job-item';
                listItem.innerHTML = `<h3>${job.title}</h3><p>${job.location} - ${job.type}</p>`;
                jobList.appendChild(listItem);
            });
        })
        .catch(error => console.error('Erro ao buscar vagas:', error));
});
