document.addEventListener("DOMContentLoaded", function () {
    const genreSelect = document.getElementById("genre");
    const addressInput = document.getElementById("address");
    const autoTagInput = document.getElementById("auto-tag");
    const createForm = document.getElementById("create-form");
    const modal = document.getElementById("modal");
    const previewCard = document.getElementById("preview-card");
    const previewImageTag = document.getElementById("preview-image-tag");

    const extractPrefecture = (address) => {
        const pattern = /(東京都|北海道|(?:京都|大阪)府|[一-龯]{2,3}県)/;
        const match = address.match(pattern);
        return match ? match[0] : "";
    };

    const updateAutoTags = () => {
        const genre = genreSelect.value.trim();
        const prefecture = extractPrefecture(addressInput.value).trim();
        autoTagInput.value = [genre && `#${genre}`, prefecture && `#${prefecture}`].filter(Boolean).join(" ");
    };

    const updatePreview = (event) => {
        event.preventDefault();
        const name = document.getElementById("name").value;
        const genre = genreSelect.value;
        const address = addressInput.value;
        const description = document.getElementById("description").value;
        const image = document.getElementById("image").files[0];

        document.getElementById("preview-name").textContent = name;
        document.getElementById("preview-genre").textContent = `#${genre}`;
        document.getElementById("preview-address").textContent = `#${extractPrefecture(address)}`;
        document.getElementById("preview-description").textContent = description;

        if (image) {
            const reader = new FileReader();
            reader.onload = (e) => (previewImageTag.src = e.target.result);
            reader.readAsDataURL(image);
        } else {
            previewImageTag.src = "https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg";
        }
        previewCard.style.display = "block";
    };

    genreSelect.addEventListener("change", updateAutoTags);
    addressInput.addEventListener("input", updateAutoTags);
    document.getElementById("preview-button").addEventListener("click", updatePreview);

    document.getElementById("show-form-btn").addEventListener("click", () => {
        modal.style.display = "none";
        createForm.style.display = "block";
        createForm.classList.add("open");
    });

    document.getElementById("close-form-btn").addEventListener("click", () => {
        createForm.style.display = "none";
        modal.style.display = "block";
        createForm.classList.remove("open");
    });

    document.getElementById("close-preview-btn").addEventListener("click", () => {
        previewCard.style.display = "none";
    });
});
