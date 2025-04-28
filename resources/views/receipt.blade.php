<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .receipt-content .logo a:hover {
            text-decoration: none;
            color: #7793C4;
        }

        .receipt-content .invoice-wrapper {
            background: #FFF;
            border: 1px solid #CDD3E2;
            box-shadow: 0px 0px 1px #CCC;
            padding: 40px 40px 60px;
            margin-top: 40px;
            border-radius: 4px;
        }

        .receipt-content .invoice-wrapper .line-items .print a {
            display: inline-block;
            border: 1px solid #9CB5D6;
            padding: 13px 13px;
            border-radius: 5px;
            color: #708DC0;
            font-size: 13px;
            -webkit-transition: all 0.2s linear;
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            transition: all 0.2s linear;
        }

        .receipt-content .invoice-wrapper .line-items .print a:hover {
            text-decoration: none;
            border-color: #333;
            color: #333;
        }

        .receipt-content {
            background: #ECEEF4;
        }

        @media (min-width: 1200px) {
            .receipt-content .container {
                width: 900px;
            }
        }

        .receipt-content .logo {
            text-align: center;
            margin-top: 50px;
        }

        .receipt-content .logo a {
            font-family: Myriad Pro, Lato, Helvetica Neue, Arial;
            font-size: 36px;
            letter-spacing: .1px;
            color: #555;
            font-weight: 300;
            -webkit-transition: all 0.2s linear;
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            transition: all 0.2s linear;
        }

        .receipt-content .invoice-wrapper .intro {
            line-height: 25px;
            color: #444;
        }

        .receipt-content .invoice-wrapper .payment-info {
            margin-top: 25px;
            padding-top: 15px;
        }

        .receipt-content .invoice-wrapper .payment-info span {
            color: #A9B0BB;
        }

        .receipt-content .invoice-wrapper .payment-info strong {
            display: block;
            color: #444;
            margin-top: 3px;
        }

        @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .payment-info .text-right {
                text-align: left;
                margin-top: 20px;
            }
        }

        .receipt-content .invoice-wrapper .line-items {
            margin-top: 40px;
        }

        .receipt-content .invoice-wrapper .line-items .headers {
            color: #A9B0BB;
            font-size: 13px;
            letter-spacing: .3px;
            border-bottom: 2px solid #EBECEE;
            padding-bottom: 4px;
        }

        .receipt-content .invoice-wrapper .line-items .items {
            margin-top: 8px;
            border-bottom: 2px solid #EBECEE;
            padding-bottom: 8px;
        }

        .receipt-content .invoice-wrapper .line-items .items .item {
            padding: 10px 0;
            color: #696969;
            font-size: 15px;
        }

        @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .items .item {
                font-size: 13px;
            }
        }

        .receipt-content .invoice-wrapper .line-items .items .item .amount {
            letter-spacing: 0.1px;
            color: #84868A;
            font-size: 16px;
        }

        @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .items .item .amount {
                font-size: 13px;
            }
        }

        .receipt-content .invoice-wrapper .line-items .total {
            margin-top: 30px;
        }

        .receipt-content .invoice-wrapper .line-items .total .extra-notes {
            float: left;
            width: 40%;
            text-align: left;
            font-size: 13px;
            color: #7A7A7A;
            line-height: 20px;
        }

        @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .total .extra-notes {
                width: 100%;
                margin-bottom: 30px;
                float: none;
            }
        }

        .receipt-content .invoice-wrapper .line-items .total .extra-notes strong {
            display: block;
            margin-bottom: 5px;
            color: #454545;
        }

        .receipt-content .invoice-wrapper .line-items .total .field {
            margin-bottom: 7px;
            font-size: 14px;
            color: #555;
        }

        .receipt-content .invoice-wrapper .line-items .total .field.grand-total {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        .receipt-content .invoice-wrapper .line-items .total .field.grand-total span {
            color: #20A720;
            font-size: 16px;
        }

        .receipt-content .invoice-wrapper .line-items .total .field span {
            display: inline-block;
            margin-left: 20px;
            min-width: 85px;
            color: #84868A;
            font-size: 15px;
        }

        .receipt-content .invoice-wrapper .line-items .print {
            margin-top: 50px;
            text-align: center;
        }



        .receipt-content .invoice-wrapper .line-items .print a i {
            margin-right: 3px;
            font-size: 14px;
        }

        .receipt-content .footer {
            margin-top: 40px;
            margin-bottom: 110px;
            text-align: center;
            font-size: 12px;
            color: #969CAD;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-content">
        <div class="container bootstrap snippets bootdey">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice-wrapper">
                        <div class="intro">
                            Hi <strong>{{ $student->name }}</strong>,
                            <br>
                            Ini struk pembayaran untuk tagihan <strong>{{ $invoice->title }}</strong> sebesar
                            Rp {{ number_format($invoice->amount, 0, ",", ".") }}
                        </div>

                        <div class="payment-info">
                            <div class="row">
                                <div class="col-sm-6">
                                    <span>No. Pembayaran</span>
                                    <strong>#{{ $transaction->id }}</strong>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <span>Tanggal</span>
                                    <strong>{{ $transaction->created_at->translatedFormat('d M Y h:i') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="line-items">
                            <div class="headers clearfix">
                                <div class="row">
                                    <div class="col-xs-4">Detail Pembayaran</div>
                                </div>
                            </div>
                            <div class="items">
                                <div class="row item">
                                    <div class="col-xs-4 desc">
                                        {{ $invoice->title }}
                                    </div>
                                    <div class="col-xs-3 qty">
                                        @if ($invoice->type == 1)
                                            Kredit
                                        @else
                                            One-Time Payment
                                        @endif
                                    </div>
                                    <div class="col-xs-5 amount text-right">
                                        Rp {{ number_format($invoice->amount, 0, ",", ".") }}
                                    </div>
                                </div>
                                @if ($invoice->type == 1)
                                    <div class="row item">
                                        <div class="col-xs-4 desc">
                                            Pembayaran Kredit
                                        </div>
                                        <div class="col-xs-3 qty">
                                            Tagihan yang harus dibayar beberapa kali hingga total lunas.
                                        </div>
                                    </div>
                                    <div class="row item">
                                        <div class="col-xs-4 desc">
                                            Total Pembayaran {{ count($transactions) }}/{{ $total }}
                                        </div>
                                        <div class="col-xs-3 qty">
                                            Pembayaran berhasil sebanyak {{ count($transactions) }} kali dari {{ $total }} tagihan {{ $invoice->title }}.
                                        </div>
                                    </div>
                                @else
                                    <div class="row item">
                                        <div class="col-xs-4 desc">
                                            One-Time Payment
                                        </div>
                                        <div class="col-xs-3 qty">
                                            Tagihan yang hanya dibayar 1x selama masa belajar.
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="total text-right">
                                <p class="extra-notes">
                                    <strong>Catatan Penting</strong>
                                    Harap simpan bukti pembayaran ini.
                                    Terimakasih
                                </p>
                                <div class="field">
                                    Nama <span>{{ $student->name }}</span>
                                </div>
                                <div class="field">
                                    Kelas <span>{{ $student->class }}</span>
                                </div>
                                <div class="field">
                                    Kode <span>{{ $student->code }}</span>
                                </div>
                                {{-- <div class="field grand-total">
                                    Status <span>Lunas</span>
                                </div> --}}
                            </div>

                            <div class="d-flex flex-row justify-content-between">
                                <a href="{{ url()->previous() }}" class="print no-print">
                                    <button type="button" class="btn btn-outline-primary">Kembali</button>
                                </a>
                                <div class="print no-print">
                                    <button onclick="window.print()" class="btn btn-primary">Print this
                                        receipt</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="footer">
                        Copyright © 2014. company name
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
