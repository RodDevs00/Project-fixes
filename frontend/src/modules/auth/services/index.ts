import { AxiosResponse } from "axios";
import { UserAccountInfoState } from "@types";

export type LoginKeys = {
 email: string;
 password: string;
};

export type RegistrationKeys = {
 first_name: string;
 middle_name: string;
 surname: string;
 sex: string;
 email: string;
 password: string;
 confirm_password: string;
};

export interface AuthInterface {
 login(request: LoginKeys): Promise<AxiosResponse>;
 register(request: RegistrationKeys): Promise<AxiosResponse>;
 getUserInfo(): Promise<AxiosResponse>;
 updateUserInfo(request: UserAccountInfoState, id: number): Promise<AxiosResponse>;
 logout(): void;
}

export { default as AuthService } from "./AuthService";
