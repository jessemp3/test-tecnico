const API_URL = "http://localhost/teste-tecnico/backend/api/films";
const API_PEOPLE = "http://localhost/teste-tecnico/backend/api/people";
const modal = new bootstrap.Modal(document.getElementById("filmModal"));

async function getCharacterName(characterId) {
  try {
    const response = await fetch(`${API_PEOPLE}/${characterId}`);
    const data = await response.json();
    return data.status === "success"
      ? data.data.fields.name
      : "Nome não disponível";
  } catch (error) {
    console.error("Erro ao buscar personagem:", error);
    return "Nome não disponível";
  }
}

async function getFilmDetails(episodeId) {
  try {
    const response = await fetch(API_URL);
    const data = await response.json();

    if (data.status === "success" && data.data.results) {
      const film = data.data.results.find(
        (film) => film.fields.episode_id === parseInt(episodeId)
      );

      if (film) {
        const characterIds = film.fields.characters.map((url) =>
          url.split("/").pop()
        );

        const characterPromises = characterIds.map((id) =>
          getCharacterName(id)
        );
        const characters = await Promise.all(characterPromises);

        return {
          ...film.fields,
          characters,
        };
      }
    }
    return null;
  } catch (error) {
    console.error("Erro:", error);
    return null;
  }
}

function renderFilmDetails(data) {
  if (!data) {
    console.error("Dados não encontrados");
    return;
  }

  const releaseYear = parseInt(data.release_date.split("-")[0]);
  const currentYear = new Date().getFullYear();
  const age = currentYear - releaseYear;

  const details = document.getElementById("filmDetails");
  details.innerHTML = `
    <h3>${data.title}</h3>
    <p>Episódio: ${data.episode_id}</p>
    <p>Diretor: ${data.director}</p>
    <p>Data de Lançamento: ${data.release_date}</p>
    <p>Idade do Filme: ${age} anos</p>
    <p>Produtor: ${data.producer}</p>
    <p>Descrição: ${data.opening_crawl}</p>
    <div>
      <h4>Personagens:</h4>
      <ul>
        ${data.characters.map((name) => `<li>${name}</li>`).join("")}
      </ul>
    </div>
  `;
}

document.querySelectorAll(".conteudo img").forEach((img) => {
  img.addEventListener("click", async () => {
    const episodeId = img.id;
    const data = await getFilmDetails(episodeId);
    if (data) {
      renderFilmDetails(data);
      modal.show();
    }
  });
});
