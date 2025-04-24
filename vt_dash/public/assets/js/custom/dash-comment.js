document.addEventListener("DOMContentLoaded", function () {
    const commentBtn = document.getElementById('btn-comment');
    const commentInput = document.getElementById('comment-input');

    if (!commentBtn || !commentInput) {
        console.warn("Botão ou input de comentário não encontrados.");
        return;
    }

    commentBtn.addEventListener('click', async () => {
        const comment = commentInput.value.trim();
        const dash = document.querySelector('meta[name="dashboard-hash"]')?.getAttribute('content') || '';
        const baseMeta = document.querySelector('meta[name="base-url"]');
        const baseURL = baseMeta ? baseMeta.getAttribute('content') : '';
        if (!comment) {
            Swal.fire({
                icon: 'warning',
                title: 'Ops...',
                text: 'Digite um comentário!',
            });
            return;
        }

        if (!dash) {
            Swal.fire({
                icon: 'error',
                title: 'Erro interno',
                text: 'Hash do dashboard não foi encontrado.',
            });
            return;
        }

        const payload = {
            comment,
            dash
        };

        try {
            // Mostra loading
            Swal.fire({
                title: 'Enviando...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
            });

            const res = await fetch(`${baseURL}/private-api/1/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) throw new Error('Erro ao enviar comentário');

            commentInput.value = '';

            // Mostra sucesso, espera 1s e recarrega
            Swal.fire({
                icon: 'success',
                title: 'Comentário enviado!',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
            });

            setTimeout(() => {
                window.location.reload();
            }, 1000);

        } catch (err) {
            console.error('Erro:', err);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Falha ao enviar o comentário. Tente novamente.',
            });
        }
    });
});
