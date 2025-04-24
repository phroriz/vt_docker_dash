document.addEventListener('DOMContentLoaded', function () {
    const showNps = document.querySelector('meta[name="show-nps"]')?.content === "true";
    if (!showNps) return;

    setTimeout(() => {
        Swal.fire({
            title: 'Como você avalia esse dashboard?',
            html: `
          <div id="nps-buttons" style="
            display: flex;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 0 0 1px #ccc;
          ">
            ${[1, 2, 3, 4, 5].map(i => {
                let bgColor = '';
                if (i <= 2) bgColor = '#f28b82';
                else if (i === 3) bgColor = '#fdd663';
                else bgColor = '#81c995';

                return `
                <button class="nps-btn" data-value="${i}" style="
                  flex: 1;
                  padding: 12px 0;
                  background-color: ${bgColor};
                  border: none;
                  font-weight: bold;
                  color: #000;
                  cursor: pointer;
                  transition: background-color 0.2s ease;
                  font-size: 1.1rem;
                ">${i}</button>
              `;
            }).join('')}
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 0.8rem;">
            <span>Ruim</span>
            <span>Excelente</span>
          </div>
        `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: false,
            showConfirmButton: false,
            didOpen: () => {
                const buttons = Swal.getHtmlContainer().querySelectorAll('.nps-btn');
                const userHash = document.querySelector('meta[name="dashboard-hash"]')?.content;
                const baseMeta = document.querySelector('meta[name="base-url"]');
                const baseURL = baseMeta ? baseMeta.getAttribute('content') : '';

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const nota = parseInt(btn.getAttribute('data-value'));

                        if (nota < 4) {
                            Swal.fire({
                                title: 'Como podemos melhorar?',
                                input: 'textarea',
                                inputPlaceholder: 'Deixe sua sugestão aqui...',
                                inputValidator: (value) => {
                                    if (!value) return 'Por favor, escreva uma sugestão';
                                },
                                confirmButtonText: 'Enviar sugestão',
                                showCancelButton: false,
                                allowOutsideClick: false,
                                preConfirm: (text) => {
                                    return fetch(`${baseURL}/private-api/1/nps`, {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ nota, sugestao: text, hash: userHash })
                                    }).then(response => {
                                        if (!response.ok) {
                                            throw new Error('Erro ao enviar');
                                        }
                                        return response.json();
                                    });
                                }
                            }).then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Obrigado!',
                                    text: `Sua avaliação foi: ${nota}`,
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true
                                });
                            });
                        } else {
                            // Enviar nota simples
                            fetch(`${baseURL}/private-api/1/nps`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ nota, hash: userHash })
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Obrigado!',
                                text: `Sua avaliação foi: ${nota}`,
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true
                            });
                        }
                    });
                });
            }
        });
    }, 10000);
});
