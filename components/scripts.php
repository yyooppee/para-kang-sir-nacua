<script src="/js/bootstrap.bundle.min.js"></script>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}

function editCell(productId, columnToUpdate, currentValue) {
        document.getElementById("UProductId").value = productId;
        document.getElementById("columnToUpdate").value = columnToUpdate;
        document.getElementById("newValue").value = currentValue;
        document.getElementById("newValue").style.display = "inline";
        document.getElementById("update").style.display = "inline";

        // Check if the column is Prod_Price or Inv_Item_Qty
        if (columnToUpdate == 'Prod_Price' || columnToUpdate == 'Inv_Item_Qty') {
            document.getElementById("newValue").type = "number";
            document.getElementById("newValue").step = "any"; // Allow decimal values
        } else {
            document.getElementById("newValue").type = "text";
        }
    }
</script>