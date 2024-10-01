<?php
namespace Views;
require_once("validate-session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .img-thumbnail {
            max-width: 200px;
        }
    </style>
    <title>PET HERO</title>
</head>
<body>
<main class="py-5">
    <section id="listado">
        <div class="container">
            <h2 class="text-center mb-4">Register New Pet</h2>
            <hr>
            <form action="<?php echo '/FinalProject4/' ?>Pet/newPet" method="post" enctype="multipart/form-data">
                <div class="row gy-3">

                    <!-- Pet Name -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petName" class="form-label">Pet Name</label>
                            <input type="text" name="petName" placeholder="Enter pet's name" class="form-control" required>
                        </div>
                    </div>

                    <!-- Pet Breed -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="breedID" class="form-label">Breed</label>
                            <select name="breedID" class="form-select" required>
                                <option value="" disabled selected>Select breed</option>
                                <?php foreach ($breedList as $breed) { ?>
                                    <option value="<?php echo $breed['id']; ?>">
                                        <?php echo $breed['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- Pet Image -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petImage" class="form-label">Pet Image (PNG/JPEG)</label>
                            <input type="file" name="petImage" class="form-control" accept=".png, .jpg, .jpeg" required>
                            <img id="petImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Pet Size -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petSize" class="form-label">Pet Size</label>
                            <select name="petSize" class="form-select" required>
                                <option value="" disabled selected>Select size</option>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Big">Big</option>
                            </select>
                        </div>
                    </div>

                    <!-- Vaccination Plan -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petVaccinationPlan" class="form-label">Vaccination Plan (PDF/Image)</label>
                            <input type="file" name="petVaccinationPlan" class="form-control" accept=".pdf, .png, .jpg, .jpeg" required>
                            <div id="vaccinationPlanPreview" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Pet Details -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petDetails" class="form-label">Pet Details</label>
                            <input type="text" name="petDetails" placeholder="Enter pet's details" class="form-control" maxlength="254" required>
                        </div>
                    </div>

                    <!-- Pet Video -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petVideo" class="form-label">Pet Video (MP4)</label>
                            <input type="file" name="petVideo" class="form-control" accept="video/mp4" required>
                            <video id="petVideoPreview" controls class="mt-2" style="display:none; max-width: 100%;">
                                <source src="" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>

                    <!-- Pet Weight -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petWeight" class="form-label">Pet Weight</label>
                            <select name="petWeight" class="form-select" required>
                                <option value="" disabled selected>Select weight category</option>
                                <option value="Light">0-15 kg</option>
                                <option value="Medium">15-30 kg</option>
                                <option value="Heavy">30 kg and above</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pet Age -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="petAge" class="form-label">Pet Age (Years)</label>
                            <input type="number" name="petAge" placeholder="Enter pet's age" class="form-control" min="0" required>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-outline-success w-50">Save Pet</button>
                </div>
            </form>
        </div>
    </section>
</main>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const petImageInput = document.querySelector("input[name='petImage']");
    const vaccinationPlanInput = document.querySelector("input[name='petVaccinationPlan']");
    const petVideoInput = document.querySelector("input[name='petVideo']");

    // Pet Image Preview
    petImageInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById("petImagePreview");
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });

    // Vaccination Plan Preview
    vaccinationPlanInput.addEventListener("change", function() {
        const file = this.files[0];
        const previewDiv = document.getElementById("vaccinationPlanPreview");
        previewDiv.innerHTML = ""; // Clear previous previews

        if (file) {
            const fileType = file.type;
            const reader = new FileReader();
            reader.onload = function(e) {
                if (fileType === "application/pdf") {
                    const embed = document.createElement("embed");
                    embed.src = e.target.result;
                    embed.type = "application/pdf";
                    embed.width = "200";
                    embed.height = "300";
                    previewDiv.appendChild(embed);
                    } else if (fileType.startsWith("image/")) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-thumbnail");
                        img.style.maxWidth = "200px";
                        previewDiv.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Pet Video Preview
        petVideoInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const videoPreview = document.getElementById("petVideoPreview");
                const source = videoPreview.querySelector("source");
                const reader = new FileReader();
                reader.onload = function(e) {
                    source.src = e.target.result;
                    videoPreview.style.display = "block";
                    videoPreview.load();
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
</body>
</html>
