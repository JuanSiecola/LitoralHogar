export type TipoOperacion = 'Venta' | 'Alquiler' | string;

export type CurrencyCode = 'USD' | 'UYU';

export interface FormatPrecioOptions {
    incluirSufijo?: boolean;
    monedaPorDefecto?: CurrencyCode;
}

export function monedaPorOperacion(
    tipoOperacion: TipoOperacion | null | undefined,
    monedaPorDefecto: CurrencyCode = 'USD',
): CurrencyCode {
    if (tipoOperacion === 'Alquiler') return 'UYU';
    if (tipoOperacion === 'Venta') return 'USD';
    return monedaPorDefecto;
}

export function formatNumero(valor: number | string | null | undefined): string {
    const num = Number(valor);
    if (!Number.isFinite(num)) return '0';
    return new Intl.NumberFormat('es-UY').format(num);
}

export function formatPrecio(
    precio: number | string | null | undefined,
    tipoOperacion: TipoOperacion | null | undefined,
    options: FormatPrecioOptions = {},
): string {
    const { incluirSufijo = true, monedaPorDefecto = 'USD' } = options;
    const moneda = monedaPorOperacion(tipoOperacion, monedaPorDefecto);
    const numero = formatNumero(precio);
    const sufijo = incluirSufijo && tipoOperacion === 'Alquiler' ? '/mes' : '';

    return `${moneda} ${numero}${sufijo}`;
}

export function etiquetaMoneda(
    tipoOperacion: TipoOperacion | null | undefined,
    monedaPorDefecto: CurrencyCode = 'USD',
): string {
    return monedaPorOperacion(tipoOperacion, monedaPorDefecto);
}
