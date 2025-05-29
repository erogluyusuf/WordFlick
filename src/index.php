<?php
// Basit PHP dizisi, kart verileri için
$cards = [
    ['title' => 'Ahmet', 'description' => 'Spor sever', 'image' => 'https://placekitten.com/300/400'],
    ['title' => 'Ayşe', 'description' => 'Kitap tutkunu', 'image' => 'https://placekitten.com/301/400'],
    ['title' => 'Mehmet', 'description' => 'Yemek meraklısı', 'image' => 'https://placekitten.com/302/400'],
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Tinder Benzeri Swipe Kart</title>
    <style>
        body {
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background: #eee; margin: 0; font-family: Arial, sans-serif;
        }
        .card-container {
            position: relative;
            width: 320px; height: 440px;
        }
        .card {
            position: absolute;
            width: 300px; height: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            overflow: hidden;
            cursor: grab;
            user-select: none;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .card img {
            width: 100%; height: 60%;
            object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card h3 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>

<div class="card-container">
    <?php foreach (array_reverse($cards) as $index => $card): ?>
        <div class="card" style="z-index: <?= $index ?>" data-index="<?= $index ?>">
            <img src="<?= htmlspecialchars($card['image']) ?>" alt="<?= htmlspecialchars($card['title']) ?>" />
            <div class="card-content">
                <h3><?= htmlspecialchars($card['title']) ?></h3>
                <p><?= htmlspecialchars($card['description']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    const cards = document.querySelectorAll('.card');
    let startX, currentX, isDragging = false;

    cards.forEach(card => {
        card.addEventListener('mousedown', startDrag);
        card.addEventListener('touchstart', startDrag);

        card.addEventListener('mousemove', drag);
        card.addEventListener('touchmove', drag);

        card.addEventListener('mouseup', endDrag);
        card.addEventListener('touchend', endDrag);
        card.addEventListener('mouseleave', endDrag);
    });

    function startDrag(e) {
        isDragging = true;
        startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        this.style.transition = 'none';
    }

    function drag(e) {
        if (!isDragging) return;
        currentX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        const diffX = currentX - startX;
        this.style.transform = `translateX(${diffX}px) rotate(${diffX * 0.1}deg)`;
    }

    function endDrag(e) {
        if (!isDragging) return;
        isDragging = false;
        const diffX = currentX - startX;
        const threshold = 100;

        if (diffX > threshold) {
            swipeCard(this, 'right');
        } else if (diffX < -threshold) {
            swipeCard(this, 'left');
        } else {
            this.style.transition = 'transform 0.3s ease';
            this.style.transform = 'translateX(0) rotate(0)';
        }
    }

    function swipeCard(card, direction) {
        card.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
        const moveX = direction === 'right' ? window.innerWidth : -window.innerWidth;
        card.style.transform = `translateX(${moveX}px) rotate(${moveX * 0.1}deg)`;
        card.style.opacity = 0;

        setTimeout(() => {
            card.remove();
            checkNoCards();
        }, 500);
    }

    function checkNoCards() {
        if (document.querySelectorAll('.card').length === 0) {
            alert('Kart kalmadı!');
        }
    }
</script>

</body>
</html>
