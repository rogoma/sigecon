const formatNumber = (
    number,
    minimumFractionDigits = 0,
    maximumFractionDigits = 2
  ) =>
    new Intl.NumberFormat('es-ES', {
      minimumFractionDigits,
      maximumFractionDigits
    }).format(number);