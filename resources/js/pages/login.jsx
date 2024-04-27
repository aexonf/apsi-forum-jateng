import Layout from "@/components/elements/layout";
import ResetPassword from "@/components/reset-password";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardDescription,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { zodResolver } from "@hookform/resolvers/zod";
import { Inertia } from "@inertiajs/inertia";
import React, { useEffect, useState } from "react";
import { useForm as reactUseForm } from "react-hook-form";
import { toast } from "sonner";
import { z } from "zod";

const formSchema = z.object({
    username: z.string().min(1, "NIP / Username harus di isi."),
    password: z.string().min(1, "Password Harus di isi."),
});

export default function LoginPage({ token, error, is_password_change }) {
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
    } = reactUseForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            username: "",
            password: "",
        },
    });
    const [openModal, setOpenModal] = useState(false);

    const submit = async (data) => {
        const { username, password } = data;
        const body = {
            username: username,
            password: password,
        };

        Inertia.post("/login", body);
    };

    useEffect(() => {
        if (error) {
            toast.error(error);
        } else if (token) {
            if (!is_password_change) {
                setOpenModal(true);
                toast.warning("Kata sandi belum diubah.");
                localStorage.setItem("token", token);
            } else {
                localStorage.setItem("token", token);
                toast.success("Berhasil masuk");
                Inertia.get("/");
            }
        }
    }, [token, error, is_password_change]);

    return (
        <Layout className={"h-screen bg-gray-50"}>
            <div className="flex items-center justify-center gap-3 w-full h-full">
                <form
                    onSubmit={handleSubmit(submit)}
                    className="w-full max-w-md"
                >
                    <Card>
                        <CardHeader className="mb-4">
                            <div className="mb-2 flex justify-center">
                                <img
                                    src="/img/apsi.png"
                                    alt="APSI Forum"
                                    className="h-28 md:h-32"
                                />
                            </div>
                            <CardTitle className="text-xl md:text-2xl text-center font-bold text-gray-800">
                                APSI Forum
                            </CardTitle>
                            <CardDescription className="text-sm md:text-base text-center font-light text-gray-600">
                                Silahkan masuk untuk melanjutkan
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="grid gap-4">
                            <div className="grid gap-2">
                                <Label
                                    className="text-sm md:text-base font-semibold text-gray-800"
                                    htmlFor="username"
                                >
                                    NIP / Username
                                </Label>
                                <Input
                                    id="username"
                                    type="text"
                                    name="username"
                                    {...register("username")}
                                    value={watch("username")}
                                    placeholder=""
                                    className={cn(
                                        errors.username &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                />
                                {errors.username && (
                                    <h4 className="text-destructive text-base">
                                        {errors.username.message}
                                    </h4>
                                )}
                            </div>
                            <div className="grid gap-2">
                                <Label
                                    className="text-sm md:text-base font-semibold text-gray-800"
                                    htmlFor="password"
                                >
                                    Kata Sandi
                                </Label>
                                <Input
                                    {...register("password")}
                                    id="password"
                                    type="password"
                                    value={watch("password")}
                                    className={cn(
                                        errors.password &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                    placeholder=""
                                />
                                {errors.password && (
                                    <h4 className="text-destructive text-base">
                                        {errors.password.message}
                                    </h4>
                                )}
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button className="w-full" type="submit">
                                Masuk
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
            <ResetPassword openModal={openModal} setOpenModal={setOpenModal} />
        </Layout>
    );
}
