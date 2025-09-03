<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement #<?php echo $payment_info->id; ?></title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555; margin: 0; }
        .receipt-container { max-width: 800px; margin: 30px auto; padding: 40px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 20px; border-bottom: 2px solid #eee; }
        .company-details { text-align: left; }
        .company-details h2 { margin: 0; font-size: 24px; color: #333; }
        .receipt-details { text-align: right; }
        .receipt-details h1 { margin: 0; font-size: 36px; color: #333; line-height: 1.2em; }
        .recipient { padding: 20px 0; }
        .payment-table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .payment-table td { padding: 8px; vertical-align: top; }
        .payment-table .heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .payment-table .item td { border-bottom: 1px solid #eee; }
        .payment-table .total td { border-top: 2px solid #eee; font-weight: bold; font-size: 18px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; }
        @media print {
            body, .receipt-container { margin: 0; box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="company-details">
                <h2><?php echo get_setting("company_name"); ?></h2>
                <p>
                    <?php echo nl2br(get_setting("company_address")); ?><br>
                    <?php echo get_setting("company_phone"); ?>
                </p>
            </div>
            <div class="receipt-details">
                <h1>REÇU</h1>
                <p>
                    <strong>Reçu #:</strong> <?php echo $payment_info->id; ?><br>
                    <strong>Date d'émission:</strong> <?php echo format_to_date(date("Y-m-d"), false); ?>
                </p>
            </div>
        </div>

        <div class="recipient">
            <strong>Reçu de :</strong><br>
            <?php echo $payment_info->chauffeur_name; ?>
        </div>

        <table class="payment-table">
            <tr class="heading">
                <td>Description</td>
                <td style="text-align: right;">Montant Payé</td>
            </tr>
            <tr class="item">
                <td>
                    Paiement de type "<?php echo lang($payment_info->type_paiement); ?>"
                    <?php if ($payment_info->mois_concerne) { echo " pour le mois de " . $payment_info->mois_concerne; } ?>.<br>
                    <small>
                        Date de paiement : <?php echo format_to_date($payment_info->date_paiement, false); ?><br>
                        Mode de paiement : <?php echo lang($payment_info->mode_paiement); ?>
                    </small>
                </td>
                <td style="text-align: right;"><?php echo to_currency($payment_info->montant); ?></td>
            </tr>
            <tr class="total">
                <td></td>
                <td style="text-align: right;">Total : <?php echo to_currency($payment_info->montant); ?></td>
            </tr>
        </table>

        <div class="footer">
            Merci.
        </div>
    </div>
</body>
</html>