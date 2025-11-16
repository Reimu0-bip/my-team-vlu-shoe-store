function addToCart(id) {
  fetch(`cart.php?add_id=${id}`, {
    method: 'GET',
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(response => response.text())
  .then(() => {
    alert("Sản phẩm đã được thêm vào giỏ!");
  })
  .catch(error => console.error('Lỗi:', error));
}
