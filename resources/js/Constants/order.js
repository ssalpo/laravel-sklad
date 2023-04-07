export const ORDER_STATUS_NEW = 1;
export const ORDER_STATUS_SEND = 2;
export const ORDER_STATUS_CANCEL = 3;

export function orderIsNew(status) {
    return status === ORDER_STATUS_NEW
}

export function orderIsSend(status) {
    return status === ORDER_STATUS_SEND
}

export function orderIsCancel(status) {
    return status === ORDER_STATUS_CANCEL
}
